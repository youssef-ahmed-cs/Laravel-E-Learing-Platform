<?php

use Illuminate\Support\Number;

# abbreviate a number (e.g., 1.5K, 2.3M)
$number = Number::abbreviate(12000);
$number = Number::abbreviate(1230000, precision: 3);
$number = Number::abbreviate(987654321, precision: 2);
$number = Number::abbreviate(1500, precision: 1);

Number::forHumans(1000000); # returns "1 million"
Number::format(1000000); # returns "1,000,000"
Number::format(123456789, locale: 'en'); # 123,456,789
Number::format(123456789, locale: 'ar'); # ١٢٣٬٤٥٦٬٧٨٩

//Number::toFileSize(1024); // 1 KB
Number::percentage(10, locale: 'ar', precision: 2);

//Number::percentage(25); # 25%
//Number::percentage((1/3)*100 , 2); # 25%
# abbreviate a number (e.g., 1.5 K, 2.3 M)
