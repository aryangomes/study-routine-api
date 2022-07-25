<?php

return [
    'user' => [
        'user_cannot_view' => 'User it is not allowed to view this :record',
        'user_cannot_get_all' => 'User it is not allowed to get all this :record',
        'user_cannot_create' => 'User it is not allowed to create :record',
        'user_cannot_update' => 'User it is not allowed to update this :record',
        'user_cannot_delete' => 'User it is not allowed to delete this :record',
    ],
    'group_work' => [
        'members' => [
            'add_new_member_not_allowed' => 'User it is not allowed to add this member to this Group Work because it is not the Owner of the Group Work.',
            'remove_member_not_allowed' => 'User is not the Owner of Group Work or the Member itself, and because that is not allowed to remove this member.',
            'view_members_not_allowed' => 'User cannot view the members of this Group Work because is not member of it.'
        ]
    ]

];
