<?php

namespace App\Helpers;

use App\Models\Settings\Setting;

use App\Models\Users\User;
use Illuminate\Support\Facades\Auth;
use Hashids\Hashids;

class Qs
{
    public static function displayError($errors)
    {
        foreach ($errors as $err) {
            $data[] = $err;
        }
        return '
                <div class="alert alert-danger alert-styled-left alert-dismissible">
									<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
									<span class="font-weight-semibold">Oops!</span> ' .
            implode(' ', $data) . '
							    </div>
                ';
    }

    public static function getAppCode()
    {
        return self::getSetting('system_title') ?: 'Zambia University College of Technology';
    }

    public static function getDefaultUserImage()
    {
        return asset('global_assets/images/user.png');
    }

    public static function getPanelOptions()
    {
        return '<div class="header-elements">
                    <div class="list-icons">
                        <a class="btn" data-action="collapse" aria-expanded="false"></a>
                    </div>
                </div>';
    }

    public static function displaySuccess($msg)
    {
        return '
 <div class="alert alert-success alert-bordered">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button> ' .
            $msg . '  </div>
                ';
    }

    public static function getTeamSA()
    {
        return ['admin', 'super_admin', 'manager_treasurer'];
    }

    public static function getTeamAccount()
    {
        return ['admin', 'super_admin', 'accountant'];
    }

    public static function getTeamSAT()
    {
        return ['admin', 'super_admin', 'instructor', 'student', 'accountant'];
    }

    public static function getTeamAcademic()
    {
        return ['admin', 'super_admin', 'instructor', 'student'];
    }
    public static function getSupportTeam()
    {
        return ['admin', 'super_admin', 'instructor', 'accountant'];
    }
    public static function getSupportTeamAll()
    {
        return ['admin', 'super_admin', 'instructor', 'student', 'accountant'];
    }

    public static function getTeamAdministrative()
    {
        return ['admin', 'super_admin', 'accountant'];
    }

    public static function hash($id)
    {
        $date = date('dMY') . 'ZUT';
        $hash = new Hashids($date, 14);
        return $hash->encode($id);
    }

    public static function getUserRecord($remove = [])
    {
        $data = [
            'first_name',
            'middle_name',
            'last_name',
            'gender',
            'image',
            'email',
            'password',
            'passport',
            'nrc'
        ];

        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }
    public static function getUserRecords($remove = [])
    {
        $data = [
            'first_name',
            'middle_name',
            'last_name',
            'gender',
            'image',
            'email',
            'password',
            'passport',
            'nrc',
            'user_type'
        ];

        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }
    public static function getUserPersonalinfor($remove = [])
    {
        $data = [
            'dob',
            'marital_status',
            'province_state',
            'town_city',
            'telephone',
            'mobile',
            'nationality',
            'street_main',
            'post_code'
        ];

        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }
    public static function getUserNKInfor($remove = [])
    {
        $data = [
            'nk_full_name',
            'nk_email',
            'relationship',
            'nkaddress',
            'nktel',
            'nk_relationship',
            'nk_nal_id',
            'nk_state_id',
            'nk_phone',
            'nk_town_id'
        ];

        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }
    public static function getUserACInfor($remove = [])
    {
        $data = [
            'nk_full_name',
            'nk_email',
            'relationship',
            'nkaddress',
            'nktel',
            'nk_relationship',
            'nk_nal_id',
            'nk_state_id',
            'nk_phone',
            'nk_town_id'
        ];

        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }

    public static function getStaffRecord($remove = [])
    {
        $data = ['emp_date',];

        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }

    public static function decodeHash($str, $toString = true)
    {
        $date = date('dMY') . 'ZUT';
        $hash = new Hashids($date, 14);
        $decoded = $hash->decode($str);
        return $toString ? implode(',', $decoded) : $decoded;
    }

    public static function userIsTeamAccount()
    {
        return in_array(self::getUserType(), self::getTeamAccount());
    }

    public static function userIsTeamSA()
    {
        return in_array(self::getUserType(), self::getTeamSA());
    }

    public static function userIsTeamSAT()
    {
        return in_array(self::getUserType(), self::getTeamSAT());
    }

    public static function userIsAcademic()
    {
        return in_array(self::getUserType(), self::getTeamAcademic());
    }

    public static function userIsAdministrative()
    {
        return in_array(self::getUserType(), self::getTeamAdministrative());
    }

    public static function userIsAdmin()
    {
        return self::getUserType() == 'admin';
    }

    public static function getStudentData($remove = [])
    {
        $data = ['programID', 'intakeID', 'studymodeID', 'level_id', 'typeID', 'paymentPlanID'];

        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }

    public static function getUserType()
    {
        $user = Auth::user();
        return $user?->userType->title;
    }

    public static function userIsSuperAdmin()
    {
        return self::getUserType() == 'super_admin';
    }

    public static function userIsDIF()
    {
        return self::getUserType() == 'director finance';
    }

    public static function userIsED()
    {
        return self::getUserType() == 'executive director';
    }

    public static function userIsMT()
    {
        return self::getUserType() == 'manager_treasurer';
    }

    public static function userIsStudent()
    {
        $user = Auth::user();
        return $user->userType->title == 'student';
    }

    public static function userIsInstructor()
    {
        $user = Auth::user();
        return $user->userType->title == 'instructor';
    }

    public static function userIsParent()
    {
        $user = Auth::user();
        return $user->userType->title == 'parent';
    }

    public static function userIsStaff()
    {
        $user = Auth::user();
        return in_array($user->userType->title, self::getStaff());
    }

    public static function getStaff($remove = [])
    {
        $data =  ['super_admin', 'admin', 'instructor', 'accountant', 'librarian'];
        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }

    public static function getAllUserTypes($remove = [])
    {
        $data =  ['super_admin', 'admin', 'instructor', 'accountant', 'librarian', 'student'];
        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }

    // Check if User is Head of Super Admins (Untouchable)
    public static function headSA(int $user_id)
    {
        return $user_id === 1;
    }

    public static function userIsPTA()
    {
        return in_array(Auth::user()->user_type, self::getPTA());
    }

    public static function userIsMyChild($student_id, $parent_id)
    {
        $data = ['user_id' => $student_id, 'my_parent_id' => $parent_id];
        return StudentRecord::where($data)->exists();
    }

    public static function getSRByUserID($user_id)
    {
        //return StudentRecord::where('user_id', $user_id)->first();
    }

    public static function getPTA()
    {
        return ['super_admin', 'admin', 'instructor', 'parent'];
    }

    /*public static function filesToUpload($programme)
    {
        return ['birth_cert', 'passport',  'neco_cert', 'waec_cert', 'ref1', 'ref2'];
    }*/

    public static function getPublicUploadPath()
    {
        return 'public/uploads';
    }
    public static function getPublicUploadPathDep()
    {
        return 'public/depart';
    }

    public static function getPublicUploadPathAnnouncements()
    {
        return 'public/announcements';
    }

    public static function getUserUploadPath()
    {
        return 'uploads/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';
    }

    public static function getUploadPath($user_type)
    {
        return 'uploads/' . $user_type . '/';
    }

    public static function getFileMetaData($file)
    {
        //$dataFile['name'] = $file->getClientOriginalName();
        $dataFile['ext'] = $file->getClientOriginalExtension();
        $dataFile['type'] = $file->getClientMimeType();
        $dataFile['size'] = self::formatBytes($file->getSize());
        return $dataFile;
    }

    public static function generateUserCode()
    {
        return substr(uniqid(mt_rand()), -7, 7);
    }

    public static function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }

    public static function getSetting($type)
    {
        return Setting::where('type', $type)->first()->description;
    }

    public static function getCurrentSession()
    {
        return self::getSetting('current_session');
    }

    public static function getNextSession()
    {
        $oy = self::getCurrentSession();
        $old_yr = explode('-', $oy);
        return ++$old_yr[0] . '-' . ++$old_yr[1];
    }

    public static function getSystemName()
    {
        return self::getSetting('system_name');
    }

    public static function findStudentRecord($user_id)
    {
        return StudentRecord::where('user_id', $user_id)->first();
    }

    public static function json($msg, $ok = TRUE, $arr = [])
    {
        return $arr ? response()->json($arr) : response()->json(['ok' => $ok, 'msg' => $msg]);
    }

    public static function jsonStoreOk($msg = 'Record created successfully')
    {
        return self::json(__($msg));
    }

    public static function jsonError($msg = "Action failed")
    {
        return self::json(__($msg), false);
    }

    public static function jsonUpdateOk($msg = 'Record updated successfully')
    {
        return self::json(__($msg));
    }

    public static function jsonUpdateError($msg = "Failed to update record")
    {
        return self::json(__($msg), false);
    }

    public static function storeOk($routeName, $msg = 'Record created successfully')
    {
        return self::goWithSuccess($routeName, __($msg));
    }

    public static function deleteOk($routeName, $msg = 'Record deleted successfully')
    {
        return self::goWithSuccess($routeName, __($msg));
    }

    public static function updateOk($routeName, $msg = 'Record updated successfully')
    {
        return self::goWithSuccess($routeName, __($msg));
    }

    public static function goToRoute($goto, $status = 302, $headers = [], $secure = null)
    {
        $data = [];
        $to = (is_array($goto) ? $goto[0] : $goto) ?: 'dashboard';
        if (is_array($goto)) {
            array_shift($goto);
            $data = $goto;
        }

        return app('redirect')->to(route($to, $data), $status, $headers, $secure);
    }

    public static function goWithDanger($to = 'dashboard', $msg = NULL)
    {
        $msg = $msg ? $msg : __('Page or resourcenot found');
        return self::goToRoute($to)->with('flash_danger', $msg);
    }

    public static function goWithSuccess($to, $msg)
    {
        return self::goToRoute($to)->with('flash_success', $msg);
    }

    public static function goBackWithSuccess($msg = "Action completed successfully")
    {
        return redirect()->back()->with('flash_success', $msg);
    }

    public static function goBackWithError($msg = "Action failed")
    {
        return redirect()->back()->with('flash_error', $msg);
    }

    public static function jsonApplicationUpdateOk($msg)
    {
        return self::json(__($msg));
    }

    public static function getDaysOfTheWeek()
    {
        return ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    }
}
