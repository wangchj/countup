INSERT INTO Users VALUES(1,'Jeff','Jeff', 'Wang', 'me@test.com', null, '$2y$10$KO5HdM03CdSOrpfBGJEKNedmvN1yLGNEE9w6AK73O.0QdEg6qlPAi','2014-08-22 19:50:49', null, 'America/Chicago', null, 'weMW5GI3aQbaV-IgsdE3VS8Wish83uFI');

INSERT INTO Counters VALUES(1,1,'Coding Daily','Test functionality of this app.','America/Chicago',0,1);
INSERT INTO Counters VALUES(2,1,'Running Daily','How long I have attended graduate school.','America/Chicago',0,1);

-- # Section 1: Daily Tests

-- insert into History values(1, '2012-01-01', '2012-01-29', 'daily', 1, null);
-- insert into History values(1, '2012-01-30', null, 'miss', null, null);
insert into History values(1, '2013-01-01', '2013-01-10', 'daily', 1, null);
insert into History values(1, '2013-01-11', null, 'miss', null, null);
insert into History values(1, '2015-03-03', '2015-03-10', 'daily', 2, null);
insert into History values(1, '2015-03-11', null, 'miss', null, null);
insert into History values(1, '2015-03-16', '2015-03-20', 'daily', 2, null);
insert into History values(1, '2015-03-21', '2015-03-21', 'daily', 1, null);
insert into History values(1, '2015-03-21', '2015-03-25', 'daily', 1, null);
insert into History values(1, '2015-03-26', null, 'miss', null, null);
insert into History values(1, '2015-04-10', '2015-04-11', 'daily', 2, null);
insert into History values(1, '2015-04-12', null, 'miss', null, null);
insert into History values(1, '2015-04-15', null, 'daily', 1, null);

--insert into History values(1, '2013-01-10', '2013-01-30');
--insert into History values(1, '2014-09-01', '2014-09-18');

insert into History values(2, '2015-04-10', null, 'daily', 3, null);
