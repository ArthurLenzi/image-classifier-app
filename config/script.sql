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

