Url params generator
---

Simple url combination generator

###Example:

Input:
```
[
     'url_1',
     'url_2' => 'param_2=1',
     'url_3' => [
          'param_1=1',
           'param_2=2',
     ],
     'url_4' => [
       'param_1' => 1,
       'param_2' => 2,
     ],
     'url_5' => [
         'param_1=2',
       'param_1' => 2,
       'param_2' => [21, 2],
     ],
     'url_6' => [
         [
             'param_1=1',
             'param_2=2',
         ],
         [
             'param_1' => 3,
             'param_2' => 4,
         ],
         [
             'param_3' => 3,
             'param_4' => 4,
             'param_5' => [51, 52],
         ]
     ]
]
```
Output:
```php
[
     'url_1',
     'url_2?param_2=1',
     'url_3?param_1=1',
     'url_3?param_2=2',
     'url_4?param_1=1',
     'url_4?param_2=2',
     'url_5?param_1=2',
     'url_5?param_1=2',
     'url_5?param_2=21',
     'url_5?param_2=2',
     'url_6?param_1=1+param_2=2',
     'url_6?param_1=3+param_2=4',
     'url_6?param_3=3+param_4=4+param_5=51',
     'url_6?param_3=3+param_4=4+param_5=52',
;`