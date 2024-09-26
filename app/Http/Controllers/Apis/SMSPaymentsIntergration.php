<?php

namespace App\Http\Controllers\Apis;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\Accounting\Invoice;
use App\Models\Accounting\PaymentMethod;
use App\Models\Admissions\Student;
use App\Repositories\Accounting\StatementRepository;
use App\Repositories\Admissions\StudentRepository;
use App\Repositories\Users\UserRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

class SMSPaymentsIntergration extends Controller
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $statementRepo;


    public function __construct(
        StudentRepository $studentRepo,
        UserRepository $userRepo,
        StatementRepository $statementRepo
    ) {
        // $this->middleware(TeamSA::class, ['except' => ['destroy']]);
        // $this->middleware(SuperAdmin::class, ['only' => ['destroy']]);
        $this->statementRepo = $statementRepo;

    }
    public function collectPaymentZanaco(Request $request)
    {
        $data = $request->only(['paymentMethod', 'transaction_id', 'transaction_id', 'amount', 'student_id', 'dateDeposited','invoice','comment','nrc','passport_number']);
        $amount  = $data['amount'];
        $student_id = $data['student_id'];
        $passport = $data['passport_number'];
        $nrc = $data['nrc'];
        $payment_method= PaymentMethod::where('name',$data['paymentMethod'])->get()->first();
        $payment_method_id  = $payment_method->id;

        try {
            $user = Student::with('user.userPersonalInfo','program','study_mode','level')
                ->where(function ($query) use ($student_id, $nrc, $passport) {
                    if (!empty($student_id)) {
                        $query->where('id', '=', $student_id);
                    }

                    // Use whereHas to search in the related user table for nrc or passport
                    $query->orWhereHas('user.userPersonalInfo', function ($query) use ($nrc, $passport) {
                        $query->where('nrc', '=', $nrc)
                            ->orWhere('passport_number', '=', $passport);
                    });
                })
                ->first();
            if (!empty($user)){
                $this->statementRepo->collectPayment(
                    $amount,
                    null,
                    $student_id,
                    $payment_method_id
                );

                return response()->json([
                    'success' => true,
                    'content' => 'Your payment was received, check your account for the Receipt.'
                ], 201);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Student information not found',
                ], 401);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'content' => 'Your payment was not Successful, Please try again.',
                'errors' => $th->getMessage()
            ], 422);
        }
    }
    public function collectPaymentIndo(Request $request)
    {
        $data = $request->only(['paymentMethod', 'transaction_id', 'transaction_id', 'amount', 'student_id', 'dateDeposited','invoice','comment','nrc','passport_number']);
        $amount  = $data['amount'];
        $student_id = $data['student_id'];
        $passport = $data['passport_number'];
        $nrc = $data['nrc'];
        $payment_method= PaymentMethod::where('name',$data['paymentMethod'])->get()->first();
        $payment_method_id  = $payment_method->id;

        try {
            $user = Student::with('user.userPersonalInfo','program','study_mode','level')
                ->where(function ($query) use ($student_id, $nrc, $passport) {
                    if (!empty($student_id)) {
                        $query->where('id', '=', $student_id);
                    }

                    // Use whereHas to search in the related user table for nrc or passport
                    $query->orWhereHas('user.userPersonalInfo', function ($query) use ($nrc, $passport) {
                        $query->where('nrc', '=', $nrc)
                            ->orWhere('passport_number', '=', $passport);
                    });
                })
                ->first();
            if (!empty($user)){
                $this->statementRepo->collectPayment(
                    $amount,
                    null,
                    $student_id,
                    $payment_method_id
                );

                return response()->json([
                    'success' => true,
                    'content' => 'Your payment was received, check your account for the Receipt.'
                ], 201);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Student information not found',
                ], 401);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'content' => 'Your payment was not Successful, Please try again.',
                'errors' => $th->getMessage()
            ], 422);
        }
    }
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($data)) {
            $user = Auth::user();

            // Set the token expiration time (adjust as needed)
            //$expiresIn = Carbon::now()->addMinutes(10);
            $expiresIn = Carbon::now()->addSeconds(60);

            // Create a new token with the desired expiration time
//            $token = $user->createToken('appToken');//plainTextToken;
            $token = $user->createToken('appToken',['*'],$expiresIn)->plainTextToken;
//            $token = $user->createToken('appToken');

            return response()->json([
                'success' => true,
                'token' => $token,
                'expire_at' => $expiresIn,
                'user' => $user
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }
    }


    public function getStudentInfo(Request $request){
        {
            $user  = Auth::user();
            $data = $request->only(['student_id', 'nrc', 'application_id','passport_number']);
            $student_id =$data['student_id'];
            $nrc =$data['nrc'];
            $passport =$data['passport_number'];
            //$passport =$data['student_id'];

            $user = Student::with('user.userPersonalInfo','program','study_mode','level')
                ->where(function ($query) use ($student_id, $nrc, $passport) {
                    if (!empty($student_id)) {
                        $query->where('id', '=', $student_id);
                    }

                    // Use whereHas to search in the related user table for nrc or passport
                    $query->orWhereHas('user.userPersonalInfo', function ($query) use ($nrc, $passport) {
                        $query->where('nrc', '=', $nrc)
                            ->orWhere('passport_number', '=', $passport);
                    });
                })
                ->first();
            if (!empty($user)){
                $datas = [
                    "student_name" => $user->user->first_name.' '.$user->user->middle_name.' '.$user->user->last_name,
                    "nrc" => $user->user->userPersonalInfo->nrc,
                    "program" => $user->program->name,
                    "study_mode" => $user->study_mode->name,

                ];
                return response()->json([
                    'success' => true,
                    'student' => $datas,
                ], 200);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Student information not found',
                ], 401);
            }

        }
    }

}
