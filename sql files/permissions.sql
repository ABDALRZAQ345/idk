-- CREATE DATABASE IF NOT EXISTS `mosque` CHARACTER SET utf8 COLLATE utf8_general_ci;
USE
`mosque`;


Delete
from permissions;

INSERT INTO permissions (name)
VALUES ('recitation.store'),
       ('recitation.read'),
       ('recitation.update'),
       ('recitation.delete'),
       ('receiver.update'),
       ('receiver.store'),
       ('receiver.delete'),
       ('receiver.read'),
       ('students.store'),
       ('students.delete'),
       ('students.read'),
       ('activity.store'),
       ('activity.delete'),
       ('activity.read'),
       ('activity.cancel'),
       ('activity.update'),
       ('supervisor.read'),
       ('users.notify'),
       ('students.notify'),
       ('groups.read'),
       ('groups.update'),
       ('groups.store'),
       ('groups.delete'),
       ('student_points.read'),
       ('student_points.update'),
       ('student_points.delete'),
       ('student_points.store'),
       ('points.read'),
       ('points.delete'),
       ('points.update'),
       ('group_students.read'),
       ('group_students.update'),
       ('group_students.delete'),
       ('group_students.store'),
       ('mosque.task.read'),
       ('group.task.read'),
       ('student.task.read'),
       ('task.store'),
       ('task.update'),
       ('task.delete')
