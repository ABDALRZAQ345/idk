
-- CREATE DATABASE IF NOT EXISTS `mosque` CHARACTER SET utf8 COLLATE utf8_general_ci;
 USE `mosque`;


Delete from permissions ;

INSERT INTO permissions (name)
VALUES
   ('add_recitation'),
   ('edit_reciever'),
    ('add_reciever'),
    ('delete_reciever'),
    ('add_student'),
    ('delete_student'),
    ('add_class'),
    ('edit_class'),
    ('delete_class'),
    ('show_student_info'),
    ('add_activity'),
    ('delete_activity'),
    ('edit_activity'),
    ('show_reciever_info'),
    ('show_supervisor_info'),
    ('send_request_to_add_points'),
    ('receive_points_request'),
    ('send_notification_to_receivers'),
    ('send_notification_to_students'),
    ('show_all_groups')
