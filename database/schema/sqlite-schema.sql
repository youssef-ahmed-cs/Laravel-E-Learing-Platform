CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "email_verified_at" datetime,
  "password" varchar not null,
  "username" varchar not null,
  "role" varchar check("role" in('admin', 'student', 'instructor')) not null default 'student',
  "avatar" varchar,
  "bio" varchar,
  "phone" varchar,
  "remember_token" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "login_count" integer not null default '0',
  "deleted_at" datetime,
  "two_factor_code" varchar,
  "two_factor_expires_at" datetime,
  "is_premium" tinyint(1) not null default '0'
);
CREATE UNIQUE INDEX "users_email_unique" on "users"("email");
CREATE UNIQUE INDEX "users_username_unique" on "users"("username");
CREATE UNIQUE INDEX "users_phone_unique" on "users"("phone");
CREATE TABLE IF NOT EXISTS "password_reset_tokens"(
  "email" varchar not null,
  "token" varchar not null,
  "created_at" datetime,
  primary key("email")
);
CREATE TABLE IF NOT EXISTS "sessions"(
  "id" varchar not null,
  "user_id" integer,
  "ip_address" varchar,
  "user_agent" text,
  "payload" text not null,
  "last_activity" integer not null,
  primary key("id")
);
CREATE INDEX "sessions_user_id_index" on "sessions"("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions"("last_activity");
CREATE TABLE IF NOT EXISTS "cache"(
  "key" varchar not null,
  "value" text not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "cache_locks"(
  "key" varchar not null,
  "owner" varchar not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "jobs"(
  "id" integer primary key autoincrement not null,
  "queue" varchar not null,
  "payload" text not null,
  "attempts" integer not null,
  "reserved_at" integer,
  "available_at" integer not null,
  "created_at" integer not null
);
CREATE INDEX "jobs_queue_index" on "jobs"("queue");
CREATE TABLE IF NOT EXISTS "job_batches"(
  "id" varchar not null,
  "name" varchar not null,
  "total_jobs" integer not null,
  "pending_jobs" integer not null,
  "failed_jobs" integer not null,
  "failed_job_ids" text not null,
  "options" text,
  "cancelled_at" integer,
  "created_at" integer not null,
  "finished_at" integer,
  primary key("id")
);
CREATE TABLE IF NOT EXISTS "failed_jobs"(
  "id" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "connection" text not null,
  "queue" text not null,
  "payload" text not null,
  "exception" text not null,
  "failed_at" datetime not null default CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs"("uuid");
CREATE TABLE IF NOT EXISTS "personal_access_tokens"(
  "id" integer primary key autoincrement not null,
  "tokenable_type" varchar not null,
  "tokenable_id" integer not null,
  "name" text not null,
  "token" varchar not null,
  "abilities" text,
  "last_used_at" datetime,
  "expires_at" datetime,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "personal_access_tokens_tokenable_type_tokenable_id_index" on "personal_access_tokens"(
  "tokenable_type",
  "tokenable_id"
);
CREATE UNIQUE INDEX "personal_access_tokens_token_unique" on "personal_access_tokens"(
  "token"
);
CREATE INDEX "personal_access_tokens_expires_at_index" on "personal_access_tokens"(
  "expires_at"
);
CREATE TABLE IF NOT EXISTS "categories"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "slug" varchar not null,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE UNIQUE INDEX "categories_name_unique" on "categories"("name");
CREATE UNIQUE INDEX "categories_slug_unique" on "categories"("slug");
CREATE TABLE IF NOT EXISTS "courses"(
  "id" integer primary key autoincrement not null,
  "title" varchar not null,
  "description" text not null,
  "price" numeric not null default '0',
  "thumbnail" varchar,
  "level" varchar check("level" in('beginner', 'intermediate', 'advanced')) not null default 'beginner',
  "status" varchar check("status" in('draft', 'published', 'archived')) not null default 'draft',
  "duration" integer not null default '0',
  "instructor_id" integer not null,
  "category_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "USD" varchar,
  foreign key("instructor_id") references "users"("id") on delete cascade,
  foreign key("category_id") references "categories"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "lessons"(
  "id" integer primary key autoincrement not null,
  "title" varchar not null,
  "content" text not null,
  "duration" integer not null default '0',
  "is_free" tinyint(1) not null default '0',
  "order" integer not null default '0',
  "video_url" varchar,
  "course_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  foreign key("course_id") references "courses"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "enrollments"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "course_id" integer not null,
  "enrolled_at" datetime not null default CURRENT_TIMESTAMP,
  "completed_at" datetime,
  "progress_percentage" numeric not null default '0',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade,
  foreign key("course_id") references "courses"("id") on delete cascade
);
CREATE UNIQUE INDEX "enrollments_user_id_course_id_unique" on "enrollments"(
  "user_id",
  "course_id"
);
CREATE TABLE IF NOT EXISTS "lesson_progresses"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "enrollment_id" integer not null,
  "lesson_id" integer not null,
  "is_completed" tinyint(1) not null default '0',
  "completed_at" datetime,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade,
  foreign key("enrollment_id") references "enrollments"("id") on delete cascade,
  foreign key("lesson_id") references "lessons"("id") on delete cascade
);
CREATE UNIQUE INDEX "lesson_progresses_enrollment_id_lesson_id_user_id_unique" on "lesson_progresses"(
  "enrollment_id",
  "lesson_id",
  "user_id"
);
CREATE TABLE IF NOT EXISTS "reviews"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "course_id" integer not null,
  "rating" integer not null default '5',
  "comment" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade,
  foreign key("course_id") references "courses"("id") on delete cascade
);
CREATE UNIQUE INDEX "reviews_user_id_course_id_unique" on "reviews"(
  "user_id",
  "course_id"
);
CREATE TABLE IF NOT EXISTS "category_course"(
  "category_id" integer not null,
  "course_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("category_id") references "categories"("id") on delete cascade,
  foreign key("course_id") references "courses"("id") on delete cascade,
  primary key("category_id", "course_id")
);
CREATE TABLE IF NOT EXISTS "notifications"(
  "id" varchar not null,
  "type" varchar not null,
  "notifiable_type" varchar not null,
  "notifiable_id" integer not null,
  "data" text not null,
  "read_at" datetime,
  "created_at" datetime,
  "updated_at" datetime,
  primary key("id")
);
CREATE INDEX "notifications_notifiable_type_notifiable_id_index" on "notifications"(
  "notifiable_type",
  "notifiable_id"
);
CREATE TABLE IF NOT EXISTS "images"(
  "id" integer primary key autoincrement not null,
  "imageable_type" varchar not null,
  "imageable_id" integer not null,
  "path" varchar not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "images_imageable_type_imageable_id_index" on "images"(
  "imageable_type",
  "imageable_id"
);
CREATE TABLE IF NOT EXISTS "super_admins"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "password" varchar not null,
  "username" varchar not null,
  "bio" varchar,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "profiles"(
  "id" integer primary key autoincrement not null,
  "age" integer not null,
  "address" varchar,
  "bio" text,
  "user_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade
);
CREATE INDEX "users_user_id_index" on "users"("user_id");
CREATE INDEX "users_username_index" on "users"("username");
CREATE TABLE IF NOT EXISTS "otps"(
  "id" integer primary key autoincrement not null,
  "identifier" varchar not null,
  "token" varchar not null,
  "validity" integer not null,
  "valid" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "otps_id_index" on "otps"("id");
CREATE TABLE IF NOT EXISTS "tasks"(
  "id" integer primary key autoincrement not null,
  "title" varchar not null,
  "priority" varchar check("priority" in('low', 'medium', 'high')) not null default 'medium',
  "content" text,
  "dateline" datetime not null,
  "completed" tinyint(1) not null default '0',
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "user_id" integer not null,
  "lesson_id" integer not null,
  foreign key("user_id") references "users"("id") on delete cascade,
  foreign key("lesson_id") references "lessons"("id") on delete cascade on update cascade
);
CREATE TABLE IF NOT EXISTS "tags"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "taggable_type" varchar not null,
  "taggable_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "tags_taggable_type_taggable_id_index" on "tags"(
  "taggable_type",
  "taggable_id"
);
CREATE TABLE IF NOT EXISTS "telescope_entries"(
  "sequence" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "batch_id" varchar not null,
  "family_hash" varchar,
  "should_display_on_index" tinyint(1) not null default '1',
  "type" varchar not null,
  "content" text not null,
  "created_at" datetime
);
CREATE UNIQUE INDEX "telescope_entries_uuid_unique" on "telescope_entries"(
  "uuid"
);
CREATE INDEX "telescope_entries_batch_id_index" on "telescope_entries"(
  "batch_id"
);
CREATE INDEX "telescope_entries_family_hash_index" on "telescope_entries"(
  "family_hash"
);
CREATE INDEX "telescope_entries_created_at_index" on "telescope_entries"(
  "created_at"
);
CREATE INDEX "telescope_entries_type_should_display_on_index_index" on "telescope_entries"(
  "type",
  "should_display_on_index"
);
CREATE TABLE IF NOT EXISTS "telescope_entries_tags"(
  "entry_uuid" varchar not null,
  "tag" varchar not null,
  foreign key("entry_uuid") references "telescope_entries"("uuid") on delete cascade,
  primary key("entry_uuid", "tag")
);
CREATE INDEX "telescope_entries_tags_tag_index" on "telescope_entries_tags"(
  "tag"
);
CREATE TABLE IF NOT EXISTS "telescope_monitoring"(
  "tag" varchar not null,
  primary key("tag")
);
CREATE TABLE IF NOT EXISTS "tests"(
  "id" varchar not null,
  "name" varchar not null,
  "content" varchar not null,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  primary key("id")
);
CREATE TABLE IF NOT EXISTS "students"(
  "name" varchar not null,
  "email" varchar not null,
  "phone" varchar,
  "id" integer primary key autoincrement not null,
  "status" varchar not null default 'active',
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "skills" text
);
CREATE INDEX "students_created_by_status_index" on "students"(
  "created_by",
  "status"
);
CREATE UNIQUE INDEX "students_email_unique" on "students"("email");
CREATE UNIQUE INDEX "students_phone_unique" on "students"("phone");

INSERT INTO migrations VALUES(1,'0001_01_01_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(4,'2025_08_23_165255_create_personal_access_tokens_table',1);
INSERT INTO migrations VALUES(5,'2025_08_23_181442_create_categories_table',1);
INSERT INTO migrations VALUES(6,'2025_08_23_181717_create_courses_table',1);
INSERT INTO migrations VALUES(7,'2025_08_23_182121_create_lessons_table',1);
INSERT INTO migrations VALUES(8,'2025_08_23_182412_create_enrollments_table',1);
INSERT INTO migrations VALUES(9,'2025_08_23_182658_create_lesson_progresses_table',1);
INSERT INTO migrations VALUES(10,'2025_08_23_182828_create_reviews_table',1);
INSERT INTO migrations VALUES(11,'2025_08_24_162138_create_category_course_table',1);
INSERT INTO migrations VALUES(12,'2025_08_27_002229_create_notifications_table',1);
INSERT INTO migrations VALUES(13,'2025_08_28_151014_add_age_to_users_table',1);
INSERT INTO migrations VALUES(14,'2025_08_28_173652_remove_age_from_users_table',1);
INSERT INTO migrations VALUES(15,'2025_08_30_011336_add_login_count_to_users_table',1);
INSERT INTO migrations VALUES(16,'2025_08_30_015451_create_images_table',1);
INSERT INTO migrations VALUES(17,'2025_08_30_025006_create_super_admins_table',1);
INSERT INTO migrations VALUES(18,'2025_09_11_001322_add_column_deleted_at_to_users_table',2);
INSERT INTO migrations VALUES(19,'2025_09_14_025450_create_profiles_table',3);
INSERT INTO migrations VALUES(20,'2025_09_15_110937_add_index_to_username_in_users_table',4);
INSERT INTO migrations VALUES(21,'2025_09_17_000855_add_deleted_at_column_to_courses_table',5);
INSERT INTO migrations VALUES(22,'2019_05_11_000000_create_otps_table',6);
INSERT INTO migrations VALUES(23,'2025_09_18_044318_add_deleted_at_column_to_lessons_table',7);
INSERT INTO migrations VALUES(25,'2025_09_20_140758_create_tasks_table',8);
INSERT INTO migrations VALUES(27,'2025_09_23_165519_create_tags_table',9);
INSERT INTO migrations VALUES(28,'2025_09_25_002326_create_telescope_entries_table',10);
INSERT INTO migrations VALUES(29,'2025_09_25_194948_tests',11);
INSERT INTO migrations VALUES(30,'2025_09_25_204934_create_tests_table',12);
INSERT INTO migrations VALUES(31,'2025_09_28_014647_add_usd_to_courses_table',13);
INSERT INTO migrations VALUES(32,'2025_10_02_010909_add_two_factor_code_and_expires_at_to_users_table',14);
INSERT INTO migrations VALUES(33,'2025_10_02_054803_create_students_table',15);
INSERT INTO migrations VALUES(34,'2025_10_03_060506_add_ispremium_column_to_users_table',16);
INSERT INTO migrations VALUES(35,'2025_10_05_001358_add_deleted_at_to_categories_table',17);
INSERT INTO migrations VALUES(36,'2025_10_06_063108_add_skills_to_students_table',18);
