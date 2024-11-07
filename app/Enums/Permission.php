<?php

namespace App\Enums;

enum Permission: string
{
    case RECITATION_READ = 'recitation.read';

    case RECITATION_STORE = 'recitation.store';

    case RECITATION_UPDATE = 'recitation.update';

    case RECITATION_DELETE = 'recitation.delete';

    case RECEIVER_READ = 'receiver.read';

    case RECEIVER_STORE = 'receiver.store';

    case RECEIVER_UPDATE = 'receiver.update';

    case RECEIVER_DELETE = 'receiver.delete';

    case STUDENT_READ = 'student.read';

    case STUDENT_STORE = 'student.store';

    case STUDENT_UPDATE = 'student.update';

    case STUDENT_DELETE = 'student.delete';

    case STUDENT_NOTIFY = 'student.notify';

    case STUDENT_POINTS_READ = 'student.points.read';

    case STUDENT_POINTS_STORE = 'student.points.store';

    case STUDENT_POINTS_UPDATE = 'student.points.update';

    case STUDENT_POINTS_DELETE = 'student.points.delete';

    case STUDENT_TASK_READ = 'student.task.read';

    case ACTIVITY_READ = 'activity.read';

    case ACTIVITY_STORE = 'activity.store';

    case ACTIVITY_UPDATE = 'activity.update';

    case ACTIVITY_DELETE = 'activity.delete';

    case ACTIVITY_CANCEL = 'activity.cancel';

    case SUPERVISOR_READ = 'supervisor.read';

    case SUPERVISOR_STORE = 'supervisor.store';

    case SUPERVISOR_UPDATE = 'supervisor.update';

    case SUPERVISOR_DELETE = 'supervisor.delete';

    case USER_READ = 'user.read';

    case USER_STORE = 'user.store';

    case USER_UPDATE = 'user.update';

    case USER_DELETE = 'user.delete';

    case USER_NOTIFY = 'user.notify';

    case GROUP_READ = 'group.read';

    case GROUP_STORE = 'group.store';

    case GROUP_UPDATE = 'group.update';

    case GROUP_DELETE = 'group.delete';

    case GROUP_STUDENTS_READ = 'group.students.read';

    case GROUP_STUDENTS_STORE = 'group.students.store';

    case GROUP_STUDENTS_UPDATE = 'group.students.update';

    case GROUP_STUDENTS_DELETE = 'group.students.delete';

    case POINTS_READ = 'points.read';

    case POINTS_STORE = 'points.store';

    case POINTS_UPDATE = 'points.update';

    case POINTS_DELETE = 'points.delete';

    case MOSQUE_TASK_READ = 'mosque.task.read';

    case GROUP_TASK_READ = 'group.task.read';

    case TASK_STORE = 'task.store';

    case TASK_UPDATE = 'task.update';

    case TASK_DELETE = 'task.delete';

}
