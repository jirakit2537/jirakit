<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
     public function rules()
     {
         return [
           'fullname' => 'required',
           'phone' => 'required|digits:10' ,
           'state' => 'required',
           'city' => 'required',
           'country' => 'required',
           'pincode' => 'required|digits:5',
         ];


     }
     public function messages()
   {
   return [
     'fullname.required' => 'กรุณากรอก ชื่อนามสกุล',
     'phone.digits'  => 'กรุณากรอก เบอร์โทรศัพท์เป็นตัวเลขเท่านั้น และมี 10 หลัก',
          'phone.required'  => 'กรุณากรอก เบอร์โทรศัพท์',

     'state.required'  => 'กรุณากรอกที่อยู่ 1',
        'city.required'  => 'กรุณากรอกที่อยู่ 2',
           'country.required'  => 'กรุณากรอกจังหวัด',

                 'pincode.digits'  => 'กรุณากรอก ไปรษณีเป็นตัวเลขเท่านั้น  และมี 5 หลัก',
              'pincode.required'  => 'กรุณากรอก รหัสไปรษณี ',
   ];
   }
}
