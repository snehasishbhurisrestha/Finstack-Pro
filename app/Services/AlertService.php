<?php

namespace App\Services;

use App\Models\SessionAssignment;
use App\Models\ParentChildLink;

class AlertService
{
    public static function getSessionUsers($sessionId)
    {
        $assignments = SessionAssignment::where('session_id', $sessionId)
            ->where('role', 'player')
            ->with('user.profile')
            ->get();

        $userIds = [];

        foreach ($assignments as $assign) {

            // Player
            if ($assign->user_id) {
                $userIds[] = $assign->user_id;
            }

            // Parent(s)
            if ($assign->user && $assign->user->profile) {

                $parents = ParentChildLink::where(
                    'child_profile_id',
                    $assign->user->profile->id
                )->where('status','active')->get();

                foreach ($parents as $parent) {
                    $userIds[] = $parent->parent_id;
                }
            }
        }

        return array_unique($userIds);
    }
}