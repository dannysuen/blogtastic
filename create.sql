create table Categories (
  CategoryID int PRIMARY KEY,
  CategoryName text
);

create table Entries (
  EntryID int PRIMARY KEY,
  CategoryID int,
  DatePosted text,
  Subject text,
  Body text
);

create table Comments (
  CommentID int PRIMARY KEY,
  BlogID int,
  DatePosted text,
  Name text,
  Comment text
);

create table Logins (
  ID int PRIMARY KEY,
  Username text,
  Password text
);
