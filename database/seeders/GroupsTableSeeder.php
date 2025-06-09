<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use App\Models\GroupMaterial;
use App\Models\GroupMessage;
use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{
    public function run(): void
    {
        // استخدام مستخدمين موجودين
        $owner = User::first();
        $member = User::skip(1)->first();

        if (!$owner || !$member) {
            return;
        }

        // إنشاء مجموعة للاختبار
        $group = Group::create([
            'name' => 'مجموعة الاختبار',
            'short_name' => 'test-group',
            'description' => 'هذه مجموعة للاختبار',
            'visibility' => 'public',
            'join_policy' => 'free',
            'max_members' => 100,
            'owner_id' => $owner->user_handle,
        ]);

        // إضافة الأعضاء
        $group->members()->attach($owner->user_handle, ['role' => 'owner']);
        $group->members()->attach($member->user_handle, ['role' => 'member']);

        // إضافة مواد تعليمية
        GroupMaterial::create([
            'group_id' => $group->id,
            'title' => 'مادة تجريبية',
            'url' => 'https://example.com/material1',
            'type' => 'document',
            'description' => 'وصف المادة التجريبية',
        ]);

        // إضافة رسائل
        GroupMessage::create([
            'group_id' => $group->id,
            'user_id' => $owner->user_handle,
            'content' => 'مرحباً بكم في المجموعة!',
            'is_pinned' => false,
        ]);

        GroupMessage::create([
            'group_id' => $group->id,
            'user_id' => $member->user_handle,
            'content' => 'شكراً على الترحيب!',
            'is_pinned' => false,
        ]);
    }
} 