create table Categories (
  CategoryID integer primary key,
  CategoryName text
);

create table Entries (
  EntryID integer primary key,
  CategoryID int,
  DatePosted text,
  Subject text,
  Body text
);

create table Comments (
  CommentID integer primary key,
  BlogID int,
  DatePosted text,
  Name text,
  Comment text
);

create table Logins (
  ID integer primary key,
  Username text,
  Password text
);
