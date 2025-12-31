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

            // User Assignments
            'user-assignment.index',
            'user-assignment.create',
            'user-assignment.update',
            'user-assignment.delete',



 // task-cat Assignments
            'task-cat.index',
            'task-cat.create',
            'task-cat.update',
            'task-cat.delete',

             // test-cat Assignments
            'test-cat.index',
            'test-cat.create',
            'test-cat.update',
            'test-cat.delete',


            // task Assignments
            'task.index',
            'task.create',
            'task.update',
            'task.delete',

            // Announcemnt
            'announcement.index',
            'announcement.create',
            'announcement.update',
            'announcement.delete',

            
            // mcq-cat
            'mcq-category.index',
            'mcq-category.create',
            'mcq-category.update',
            'mcq-category.delete',

            // mcq-bank
            'mcq.banks.index',
            'mcq.banks.create',
            'mcq.banks.update',
            'mcq.banks.delete',


             // assign-paper
            'assign-paper.index',
            'assign-paper.create',
            'assign-paper.update',
            'assign-paper.delete',
            'assign-paper.check-result',
            'assign-paper.notuse',
            'assign-paper.nouse',
            'assign-paper.noouse',


            

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