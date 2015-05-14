INSERT INTO Users VALUES(
    /*userId*/   1,
    /*userName*/ 'Jeff',
    /*foreName*/ 'Jeff',
    /*surname*/  'Wang',
    /*gender*/   'male',
    /*email*/    'me@test.com',
    /*fbId*/     null,
    /*phash*/    '$2y$10$KO5HdM03CdSOrpfBGJEKNedmvN1yLGNEE9w6AK73O.0QdEg6qlPAi',
    /*joinDate*/ '2014-08-22 19:50:49',
    /*location*/ null,
    /*timeZone*/ 'America/Chicago',
    /*picture*/  null,
    /*authKey*/  'weMW5GI3aQbaV-IgsdE3VS8Wish83uFI'
);

INSERT INTO Users VALUES(
    /*userId*/   2,
    /*userName*/ null,
    /*foreName*/ 'Eugenie',
    /*surname*/  'Bouchard',
    /*gender*/   'female',
    /*email*/    'a@a.com',
    /*fbId*/     null,
    /*phash*/    '$2y$10$KO5HdM03CdSOrpfBGJEKNedmvN1yLGNEE9w6AK73O.0QdEg6qlPAi',
    /*joinDate*/ '2014-08-22 19:50:49',
    /*location*/ null,
    /*timeZone*/ 'America/Chicago',
    /*picture*/  null,
    /*authKey*/  'weMW5GI3aQbaV-IgsdE3VS8Wish83uFI'
);

INSERT INTO Users VALUES(
    /*userId*/   3,
    /*userName*/ null,
    /*foreName*/ 'Nei',
    /*surname*/  'Nishikori',
    /*gender*/   'male',
    /*email*/    'b@b.com',
    /*fbId*/     null,
    /*phash*/    '$2y$10$KO5HdM03CdSOrpfBGJEKNedmvN1yLGNEE9w6AK73O.0QdEg6qlPAi',
    /*joinDate*/ '2014-08-22 19:50:49',
    /*location*/ null,
    /*timeZone*/ 'America/Chicago',
    /*picture*/  null,
    /*authKey*/  'weMW5GI3aQbaV-IgsdE3VS8Wish83uFI'
);

INSERT INTO Users VALUES(
    /*userId*/   4,
    /*userName*/ null,
    /*foreName*/ 'Ana',
    /*surname*/  'Ivanovic',
    /*gender*/   'female',
    /*email*/    'c@c.com',
    /*fbId*/     null,
    /*phash*/    '$2y$10$KO5HdM03CdSOrpfBGJEKNedmvN1yLGNEE9w6AK73O.0QdEg6qlPAi',
    /*joinDate*/ '2014-08-22 19:50:49',
    /*location*/ null,
    /*timeZone*/ 'America/Chicago',
    /*picture*/  null,
    /*authKey*/  'weMW5GI3aQbaV-IgsdE3VS8Wish83uFI'
);

INSERT INTO Users VALUES(
    /*userId*/   5,
    /*userName*/ null,
    /*foreName*/ 'Roger',
    /*surname*/  'Federer',
    /*gender*/   'male',
    /*email*/    'd@d.com',
    /*fbId*/     null,
    /*phash*/    '$2y$10$KO5HdM03CdSOrpfBGJEKNedmvN1yLGNEE9w6AK73O.0QdEg6qlPAi',
    /*joinDate*/ '2014-08-22 19:50:49',
    /*location*/ null,
    /*timeZone*/ 'America/Chicago',
    /*picture*/  null,
    /*authKey*/  'weMW5GI3aQbaV-IgsdE3VS8Wish83uFI'
);

INSERT INTO Users VALUES(
    /*userId*/   6,
    /*userName*/ null,
    /*foreName*/ 'Michael',
    /*surname*/  'Jordan',
    /*gender*/   'male',
    /*email*/    'e@e.com',
    /*fbId*/     null,
    /*phash*/    '$2y$10$KO5HdM03CdSOrpfBGJEKNedmvN1yLGNEE9w6AK73O.0QdEg6qlPAi',
    /*joinDate*/ '2014-08-22 19:50:49',
    /*location*/ null,
    /*timeZone*/ 'America/Chicago',
    /*picture*/  null,
    /*authKey*/  'weMW5GI3aQbaV-IgsdE3VS8Wish83uFI'
);

INSERT INTO Users VALUES(
    /*userId*/   7,
    /*userName*/ null,
    /*foreName*/ 'Ivy',
    /*surname*/  'Chung',
    /*gender*/   'female',
    /*email*/    'f@f.com',
    /*fbId*/     null,
    /*phash*/    '$2y$10$KO5HdM03CdSOrpfBGJEKNedmvN1yLGNEE9w6AK73O.0QdEg6qlPAi',
    /*joinDate*/ '2014-08-22 19:50:49',
    /*location*/ null,
    /*timeZone*/ 'America/Chicago',
    /*picture*/  null,
    /*authKey*/  'weMW5GI3aQbaV-IgsdE3VS8Wish83uFI'
);




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


insert into Follows values(1, 2);
insert into Follows values(1, 3);
insert into Follows values(1, 4);
insert into Follows values(1, 5);
insert into Follows values(2, 1);
insert into Follows values(3, 4);
insert into Follows values(6, 1);
