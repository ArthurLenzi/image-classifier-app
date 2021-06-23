create schema image_app;

create table image_app.image
(
	hash varchar(200) not null,
	hole varchar(50) not null
);

create unique index image_hash_uindex
	on image_app.image (hash);

alter table image_app.image
	add constraint image_pk
		primary key (hash);

create schema acl;

create table acl."user"
(
    email      varchar(50)           not null
        constraint user_pk
            primary key,
    name       varchar(20)           not null,
    password   varchar(200)          not null,
    valid      boolean default true  not null,
    created_at date    default now() not null
);

alter table acl."user"
    owner to postgres;

create unique index user_email_uindex
    on acl."user" (email);

create table acl.user_role
(
    email varchar(50) not null
        constraint user_role_user_email_fk
            references acl."user",
    role  varchar(20) not null
);

alter table acl.user_role
    owner to postgres;