<?php

return [

  /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */


  'accepted' => 'يجب قبول :attribute.',
  'accepted_if' => 'يجب قبول :attribute عندما :other يكون :value.',
  'active_url' => ' :attribute ليس عنوان URL صالحًا.',
  'after' => 'يجب أن يكون :attribute تاريخًا بعد :date.',
  'after_or_equal' => 'يجب أن يكون :attribute تاريخًا بعد أو يساوي :date.',
  'alpha' => 'يجب أن يحتوي :attribute على أحرف فقط.',
  'alpha_dash' => 'يجب أن يحتوي :attribute على أحرف وأرقام وشرطات وشرطات سفلية فقط.',
  'alpha_num' => 'يجب أن يحتوي :attribute على أحرف وأرقام فقط.',
  'array' => 'يجب أن يكون :attribute مصفوفة.',
  'before' => 'يجب أن يكون :attribute تاريخًا قبل :date.',
  'before_or_equal' => 'يجب أن يكون :attribute تاريخًا قبل أو يساوي :date.',
  'between' => [
    'numeric' => 'يجب أن يكون :attribute بين :min و :max.',
    'file' => 'يجب أن يكون :attribute بين :min و :max كيلوبايت.',
    'string' => 'يجب أن يكون :attribute بين :min و :max حرفًا.',
    'array' => 'يجب أن يكون :attribute بين :min و :max عنصرًا.',
  ],
  'boolean' => 'يجب أن يكون حقل :attribute صحيحًا أو خاطئًا.',
  'confirmed' => 'تأكيد :attribute لا يتطابق.',
  'current_password' => 'كلمة المرور غير صحيحة.',
  'date' => ' :attribute ليس تاريخًا صالحًا.',
  'date_equals' => 'يجب أن يكون :attribute تاريخًا يساوي :date.',
  'date_format' => ' :attribute لا يتطابق مع الشكل :format.',
  'declined' => 'يجب رفض :attribute.',
  'declined_if' => 'يجب رفض :attribute عندما :other يكون :value.',
  'different' => 'يجب أن يكون :attribute و :other مختلفين.',
  'digits' => 'يجب أن يكون :attribute :digits أرقام.',
  'digits_between' => 'يجب أن يكون :attribute بين :min و :max أرقام.',
  'dimensions' => 'ال :attribute لديها أبعاد صور غير صالحة.',
  'distinct' => 'حقل :attribute يحتوي على قيمة مكررة.',
  'email' => 'يجب أن يكون :attribute عنوان بريد إلكتروني صالحًا.',
  'ends_with' => 'يجب أن ينتهي :attribute بأحد القيم التالية: :values.',
  'enum' => 'ال :attribute المحدد غير صالح.',
  'exists' => 'ال :attribute المحدد غير صالح.',
  'file' => 'يجب أن يكون :attribute ملفًا.',
  'filled' => 'يجب أن يكون حقل :attribute مع قيمة.',
  'gt' => [
    'numeric' => 'يجب أن يكون :attribute أكبر من :value.',
    'file' => 'يجب أن يكون :attribute أكبر من :value كيلوبايت.',
    'string' => 'يجب أن يكون :attribute أكبر من :value حرفًا.',
    'array' => 'يجب أن يحتوي :attribute على أكثر من :value عنصر.',
  ],
  'gte' => [
    'numeric' => 'يجب أن يكون :attribute أكبر من أو يساوي :value.',
    'file' => 'يجب أن يكون :attribute أكبر من أو يساوي :value كيلوبايت.',
    'string' => 'يجب أن يكون :attribute أكبر من أو يساوي :value حرفًا.',
    'array' => 'يجب أن يحتوي :attribute على :value عنصر أو أكثر.',
  ],
  'image' => 'يجب أن يكون :attribute صورة.',
  'in' => 'ال :attribute المحدد غير صالح.',
  'in_array' => 'حقل :attribute غير موجود في :other.',
  'integer' => 'يجب أن يكون :attribute عددًا صحيحًا.',
  'ip' => 'يجب أن يكون :attribute عنوان IP صالحًا.',
  'ipv4' => 'يجب أن يكون :attribute عنوان IPv4 صالحًا.',
  'ipv6' => 'يجب أن يكون :attribute عنوان IPv6 صالحًا.',
  'json' => 'يجب أن يكون :attribute سلسلة JSON صالحة.',
  'lt' => [
    'numeric' => 'يجب أن يكون :attribute أقل من :value.',
    'file' => 'يجب أن يكون :attribute أقل من :value كيلوبايت.',
    'string' => 'يجب أن يكون :attribute أقل من :value حرفًا.',
    'array' => 'يجب أن يحتوي :attribute على أقل من :value عنصر.',
  ],
  'lte' => [
    'numeric' => 'يجب أن يكون :attribute أقل من أو يساوي :value.',
    'file' => 'يجب أن يكون :attribute أقل من أو يساوي :value كيلوبايت.',
    'string' => 'يجب أن يكون :attribute أقل من أو يساوي :value حرفًا.',
    'array' => 'يجب أن لا يحتوي :attribute على أكثر من :value عنصر.',
  ],
  'mac_address' => 'يجب أن يكون :attribute عنوان MAC صالحًا.',
  'max' => [
    'numeric' => 'يجب أن لا يكون :attribute أكبر من :max.',
    'file' => 'يجب أن لا يكون :attribute أكبر من :max كيلوبايت.',
    'string' => 'يجب أن لا يكون :attribute أكبر من :max حرفًا.',
    'array' => 'يجب أن لا يحتوي :attribute على أكثر من :max عنصر.',
  ],
  'mimes' => 'يجب أن يكون :attribute ملفًا من النوع: :values.',
  'mimetypes' => 'يجب أن يكون :attribute ملفًا من النوع: :values.',
  'min' => [
    'numeric' => 'يجب أن يكون :attribute على الأقل :min.',
    'file' => 'يجب أن يكون :attribute على الأقل :min كيلوبايت.',
    'string' => 'يجب أن يكون :attribute على الأقل :min حرفًا.',
    'array' => 'يجب أن يحتوي :attribute على الأقل :min عنصرًا.',
  ],
  'multiple_of' => 'يجب أن يكون :attribute مضاعفًا لـ :value.',
  'not_in' => 'ال :attribute المحدد غير صالح.',
  'not_regex' => 'تنسيق :attribute غير صالح.',
  'numeric' => 'يجب أن يكون :attribute رقمًا.',
  'password' => 'كلمة المرور غير صحيحة.',
  'present' => 'يجب أن يكون حقل :attribute موجودًا.',
  'prohibited' => 'حقل :attribute محظور.',
  'prohibited_if' => 'حقل :attribute محظور عندما :other يكون :value.',
  'prohibited_unless' => 'حقل :attribute محظور ما لم يكن :other في :values.',
  'prohibits' => 'حقل :attribute يحظر :other من الوجود.',
  'regex' => 'تنسيق :attribute غير صالح.',
  'required' => 'حقل :attribute مطلوب.',
  'required_array_keys' => 'حقل :attribute يجب أن يحتوي على مفاتيح للقيم: :values.',
  'required_if' => 'حقل :attribute مطلوب عندما يكون :other هو :value.',
  'required_unless' => 'حقل :attribute مطلوب ما لم يكن :other في :values.',
  'required_with' => 'حقل :attribute مطلوب عندما يكون :values موجودًا.',
  'required_with_all' => 'حقل :attribute مطلوب عندما تكون :values موجودة.',
  'required_without' => 'حقل :attribute مطلوب عندما لا تكون :values موجودة.',
  'required_without_all' => 'حقل :attribute مطلوب عند عدم وجود أي من :values.',
  'same' => 'يجب أن يتطابق :attribute مع :other.',
  'size' => [
    'numeric' => 'يجب أن يكون :attribute :size.',
    'file' => 'يجب أن يكون حجم :attribute :size كيلوبايت.',
    'string' => 'يجب أن يكون طول :attribute :size أحرف.',
    'array' => 'يجب أن يحتوي :attribute على :size عنصرًا.',
  ],
  'starts_with' => 'يجب أن يبدأ :attribute بأحد القيم التالية: :values.',
  'string' => 'يجب أن يكون :attribute نصًا.',
  'timezone' => 'يجب أن يكون :attribute منطقة زمنية صحيحة.',
  'unique' => 'تم استخدام :attribute بالفعل.',
  'uploaded' => 'فشل في رفع :attribute.',
  'url' => 'يجب أن يكون :attribute رابط صحيح.',
  'uuid' => 'يجب أن يكون :attribute UUID صحيح.',

  /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

  'custom' => [
    'attribute-name' => [
      'rule-name' => 'custom-message',
    ],
  ],

  /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

  'attributes' => [],

];
