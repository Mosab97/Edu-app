<?php

use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{
    private function createService($arr)
    {
        foreach ($arr as $index => $item) {
//            dd($index,$item);
            \App\Models\Service::create([
                'name' => $item['name'],
                'price' => $item['price'],
                'hour_price' => $item['hour_price'],
                'details' => $item['details'],
            ]);
        }

    }

    public function run()
    {
        $arr = [
            1 => [
                'name' => [
                    'ar' => 'خدمة المساعدة في اختيار البرنامج المحاسبي',
                    'en' => 'Help in choosing the accounting program',
                ],
                'details' => [
                    'ar' => 'تتيح لك هذه الخدمة المساعدة في امكانية اختيار البرنامج المحاسبي وفق نشاطك التحاري والاحتياجات الخاصة بالمشروع',
                    'en' => 'This service allows you to help you choose the accounting program according to your investigative activity and the project’s specific needs'
                ],
                'price' => 50,
                'hour_price' => 50,

            ],
            2 => [
                'name' => [
                    'ar' => 'تأسيس دليل الحسابات',
                    'en' => 'Establishing the chart of accounts',
                ],
                'details' => [
                    'ar' => 'تتيح لك هذه الخدمة امكانية تأسيس واعداد الدليل المحاسبي لنشاطك من خلال جلسة لمدة 4 ساعات لتمكن من استخدام البرنامج المحاسبي بكفاءة عالية',
                    'en' => 'This service allows you to establish and prepare an accounting guide for your activity through a 4-hour session to be able to use the accounting program with high efficiency',
                ],
                'price' => 70,
                'hour_price' => 50,

            ],
            3 => [
                'name' => [
                    'ar' => 'خدمة التدريب',
                    'en' => 'Training service',
                ],
                'details' => [
                    'ar' => 'تتيح لك هذه الخدمة امكانية تدريب موظفينك بشكل كامل على اي من البرامج المحاسبية من خلال تطبيق Zoom & Google Meet ,حيث نعمل على توفير افضل الاشخاص المؤهلين علميا وعمليا لتدريب الموظفين بكفاءة عالية',
                    'en' => 'This service allows you to fully train your employees on any of the accounting programs through the Zoom & Google Meet application, as we work to provide the best scientifically and practically qualified people to train employees with high efficiency',
                ],
                'price' => 90,
                'hour_price' => 50,
            ],

        ];
        $this->createService($arr);
        for ($i = 1; $i <= 6; $i++)
            \App\Models\Service::create([
                'name' => [
                    'ar' => 'name ar' . $i,
                    'en' => 'name en' . $i,
                ],
                'details' => [
                    'ar' => 'details ar',
                    'en' => 'details en',
                ],
                'price' => ($i + 100),
                'hour_price' => ($i + 100),

            ]);
    }
}
