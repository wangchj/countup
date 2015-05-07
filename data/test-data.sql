INSERT INTO Users VALUES(1,'Jeff','Jeff', 'Wang', 'me@test.com', null, '$2y$10$KO5HdM03CdSOrpfBGJEKNedmvN1yLGNEE9w6AK73O.0QdEg6qlPAi','2014-08-22 19:50:49', null, 'America/Chicago', null, 'weMW5GI3aQbaV-IgsdE3VS8Wish83uFI');

INSERT INTO Counters VALUES(
    /*counterId*/ 1,
    /*userId*/    1,
    /*label*/     'Coding Daily',
    /*summary*/   'Test functionality of this app.',
    /*timeZone*/  'America/Chicago',
    /*startDate*/ '2015-04-20',
    /*type*/      'daily',
    /*every*/     1,
    /*on*/        null,
    /*active*/    1,
    /*public*/    1,
    /*dispOrder*/ 0
);

INSERT INTO Counters VALUES(
    /*counterId*/ 2,
    /*userId*/    1,
    /*label*/     'Running',
    /*summary*/   'Run for at least 20 minutes every other day',
    /*timeZone*/  'America/Chicago',
    /*startDate*/ '2015-04-10',
    /*type*/      'daily',
    /*every*/     2,
    /*on*/        null,
    /*active*/    1,
    /*public*/    1,
    /*dispOrder*/ 1
);

-- # Section 1: Daily Tests

insert into History values(1, '2015-03-03', 0);
insert into History values(1, '2015-03-04', 0);
insert into History values(1, '2015-03-05', 0);
insert into History values(1, '2015-03-06', 0);
insert into History values(1, '2015-03-07', 0);
insert into History values(1, '2015-03-08', 1);

insert into History values(1, '2015-03-15', 0);
insert into History values(1, '2015-03-16', 0);
insert into History values(1, '2015-03-17', 0);
insert into History values(1, '2015-03-18', 0);
insert into History values(1, '2015-03-19', 0);
insert into History values(1, '2015-03-21', 0);
insert into History values(1, '2015-03-23', 1);    

insert into History values(1, '2015-04-10', 0);
insert into History values(1, '2015-04-11', 0);
insert into History values(1, '2015-04-12', 1);

insert into History values(1, '2015-04-15', 0);
insert into History values(1, '2015-04-16', 0);
insert into History values(1, '2015-04-17', 0);
insert into History values(1, '2015-04-18', 0);
insert into History values(1, '2015-04-19', 0);
insert into History values(1, '2015-04-21', 0);
insert into History values(1, '2015-04-22', 1);

insert into History values(1, '2015-04-23', 0);
insert into History values(1, '2015-04-24', 0);
insert into History values(1, '2015-04-25', 0);

insert into History values(2, '2015-04-10', 0);
insert into History values(2, '2015-04-12', 0);
insert into History values(2, '2015-04-14', 0);
insert into History values(2, '2015-04-16', 0);
insert into History values(2, '2015-04-18', 1);
insert into History values(2, '2015-04-22', 0);
insert into History values(2, '2015-04-24', 0);
