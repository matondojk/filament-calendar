<?php

return [
    'notifications' => [
        'new_invitation_title' => 'دعوة جديدة: :title',
        'new_invitation_body' => 'لقد تمت دعوتك لحدث جديد في :date',
        'reminder_title' => 'تذكير: :title',
        'reminder_body' => 'سيبدأ الحدث خلال 24 ساعة: :date',
    ],
    'emails' => [
        'invitation_subject' => 'دعوة: :title',
        'reminder_subject' => 'تذكير بحدث: :title',
        'reminder_body' => 'تذكير: سيبدأ الحدث \':title\' خلال 24 ساعة في :date. :location',
        'invitation_header' => 'لقد تمت دعوتك إلى حدث!',
        'details' => 'التفاصيل:',
        'footer' => 'يمكنك الوصول إلى لوحة التحكم لتأكيد أو رفض الحضور. كما تم إرفاق ملف تقويم .ics بهذا البريد الإلكتروني حتى تتمكن من إضافة هذا الحدث إلى تقويمك الشخصي.',
    ],
    'resource' => [
        'navigation_label' => 'الأحداث',
        'model_label' => 'حدث',
        'plural_model_label' => 'الأحداث',
        'form' => [
            'event_details' => 'تفاصيل الحدث',
            'event_details_desc' => 'المعلومات الرئيسية حول الحدث.',
            'title' => 'عنوان الحدث',
            'description' => 'الوصف',
            'starts_at' => 'يبدأ في',
            'ends_at' => 'ينتهي في',
            'format' => 'شكل الحدث',
            'in_person' => 'حضور شخصي',
            'virtual' => 'افتراضي',
            'platform' => 'المنصة',
            'meeting_link' => 'رابط الاجتماع',
            'open_link' => 'افتح الرابط',
            'location' => 'الموقع الفعلي',
            'participants' => 'المشاركون',
            'participants_desc' => 'حدد المستخدمين لدعوتهم إلى هذا الحدث.',
            'guests' => 'الضيوف',
        ],
        'table' => [
            'title' => 'العنوان',
            'creator' => 'المنشئ',
            'starts_at' => 'يبدأ في',
            'ends_at' => 'ينتهي في',
            'format' => 'الشكل',
            'where' => 'أين',
            'guests' => 'الضيوف',
            'upcoming' => 'الأحداث القادمة',
        ],
        'actions' => [
            'create_event' => 'حدث جديد',
        ],
        'tabs' => [
            'all' => 'كل الأحداث',
            'my_events' => 'أحداثي',
            'invited' => 'المدعوون',
        ],
    ],
];
