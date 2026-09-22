<?php

return [
    'notifications' => [
        'new_invitation_title' => 'Novo Convite: :title',
        'new_invitation_body' => 'Você foi convidado para um novo evento no dia :date',
        'reminder_title' => 'Lembrete: :title',
        'reminder_body' => 'O evento começará em 24 horas: :date',
    ],
    'emails' => [
        'invitation_subject' => 'Convite: :title',
        'reminder_subject' => 'Lembrete de Evento: :title',
        'reminder_body' => 'Lembrete: O evento \':title\' começará em 24 horas, no dia :date. :location',
        'invitation_header' => 'Você foi convidado para um evento!',
        'details' => 'Detalhes:',
        'footer' => 'Acesse o painel para confirmar ou recusar sua presença. Além disso, um arquivo de calendário .ics está em anexo para que você possa adicionar este evento à sua agenda pessoal.',
    ],
    'resource' => [
        'navigation_label' => 'Eventos',
        'model_label' => 'Evento',
        'plural_model_label' => 'Eventos',
        'form' => [
            'event_details' => 'Detalhes do Evento',
            'event_details_desc' => 'Informações principais do evento.',
            'title' => 'Título do Evento',
            'description' => 'Descrição',
            'starts_at' => 'Inicia Em',
            'ends_at' => 'Termina Em',
            'format' => 'Formato do Evento',
            'in_person' => 'Presencial',
            'virtual' => 'Virtual',
            'platform' => 'Plataforma',
            'meeting_link' => 'Link da Reunião',
            'open_link' => 'Abrir Link',
            'location' => 'Local Físico',
            'participants' => 'Participantes',
            'participants_desc' => 'Selecione os usuários convidados.',
            'guests' => 'Convidados',
        ],
        'table' => [
            'title' => 'Título',
            'creator' => 'Criador',
            'starts_at' => 'Início',
            'ends_at' => 'Término',
            'format' => 'Formato',
            'where' => 'Local/Link',
            'guests' => 'Convidados',
            'upcoming' => 'Próximos Eventos',
        ],
        'actions' => [
            'create_event' => 'Novo Evento',
        ],
        'tabs' => [
            'all' => 'Todos Eventos',
            'my_events' => 'Meus Eventos',
            'invited' => 'Convites',
        ],
    ],
];
