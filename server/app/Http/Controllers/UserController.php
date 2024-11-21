<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Facades\Validator;
use App\Mail\TesteMail;

class UserController extends Controller
{
    protected $mailController;

    public function __construct(MailController $mailController)
    {
        $this->mailController = $mailController;
    }

    public function index($id)
    {
        $users = User::select('id', 'name', 'email')->where('id', '!=', $id)->get();
        return response()->json($users, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 400);
        }

        $apiKey = env('EMAILABLE_API_KEY');
        $email = $request->email;
        $apiEndpoint = "https://api.emailable.com/v1/verify?email={$email}&api_key={$apiKey}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiEndpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        $response = curl_exec($ch);
        curl_close($ch); 

        if (curl_errno($ch)) {
            return response()->json([
                'success' => false,
                'message' => 'Erro na requisição!',
                'error' => curl_error($ch)
            ], 404);
        } else {
            $responseData = json_decode($response);

            if (isset($responseData->state) && $responseData->state !== 'deliverable') {
                return response()->json([
                    'success' => false,
                    'message' => 'O email não existe.'
                ], 400);
            } else {
                $user = User::create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'password' => bcrypt($request->input('password')),
                    'status' => "not authenticated"
                ]);

                $uniqueNumber = $this->generateUniqueNumber($user->id);
                $user->remember_token = $uniqueNumber;
                $user->save();

                $details = [
                    'name' => $user->name,
                    'codigo' => $uniqueNumber
                ];

                Mail::to($user->email)->send(new TesteMail('teste.mail', $details));

                return response()->json([
                    'success' => true,
                    'message' => 'Usuário criado com sucesso e código enviado!',
                    'user' => $user
                ], 201);
            }
        }
    }

    private function generateUniqueNumber($id)
    {
        $randomNumber = rand(10000, 99999);
        $uniqueNumber = ($id + $randomNumber) % 100000;
        return str_pad($uniqueNumber, 5, '0', STR_PAD_LEFT);
    }

    public function authenticated(Request $request)
    {
        $user = User::where('remember_token', $request->token)->first();
        if ($user) {
            $user->status = "authenticated";
            $user->authenticated_at = Carbon::now();
            $user->remember_token = null;
            $user->save();

            return response()->json(['message' => 'Usuário autenticado com sucesso.'], 200);
        }

        return response()->json(['message' => 'Usuário não encontrado.'], 404);
    }
}
