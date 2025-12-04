<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // SESSION
            'session.index',
            'session.create',
            'session.update',
            'session.delete',

            // PROGRAM
            'program.index',
            'program.create',
            'program.update',
            'program.delete',

            // SESSION + PROGRAM ASSIGN
            'session_program.index',
            'session_program.create',
            'session_program.update',
            'session_program.delete',

            // Student
            'student.index',
            'student.create',
            'student.update',
            'student.delete',

            // teacher
            'teacher.index',
            'teacher.create',
            'teacher.update',
            'teacher.delete',

            // Student Category
            'stu_category.index',
            'stu_category.create',
            'stu_category.update',
            'stu_category.delete',

            // Student Category
            'subject.index',
            'subject.create',
            'subject.update',
            'subject.delete',

            // Class Subject
            'class-subject.index',
            'class-subject.create',
            'class-subject.update',
            'class-subject.delete',

            // Class Teacher
            'class-teacher.index',
            'class-teacher.create',
            'class-teacher.update',
            'class-teacher.delete',

            // ROLES
            'role.index',
            'role.create',
            'role.update',
            'role.delete',

            // USERS
            'user.index',
            'user.create',
            'user.update',
            'user.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);

        // Admin gets everything
        $admin->givePermissionTo(Permission::all());

        // Manager only limited
        $manager->syncPermissions([
            'session.index',
            'session.create',

            'session_program.index',
            'session_program.create',
        ]);
    }
}

/*
 * ------------------------------------------------------------
 *  Permissions & Role Seeder Setup Commands
 *  Author:  Zain Mughal
 * ------------------------------------------------------------
 *
 *  Step 1 — Clear All Caches
 *  ----------------------------------------
 *  php artisan cache:clear
 *  php artisan config:clear
 *  php artisan permission:cache-reset
 *
 *
 *  Step 2 — Run RolePermission Seeder
 *  ----------------------------------------
 *  php artisan db:seed --class=RolePermissionSeeder
 *
 *  --------------------------------------------
 *   GitHub Upload Commit
 *   git add .
 *   git commit -m "Your update message"
 *   git push
 *
 * ------------------------------------------------------------
 */