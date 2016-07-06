# Web API Prototype Server

A simple web API demo using the Silex PHP Framework.

## Installation
1. Make sure to have PHP 5.5 or higher, MySQL or MariaDB, and a
   web server (such as Apache) installed.
2. Install all the dependencies via [Composer](https://getcomposer.org/)
3. Edit `web/index.php` to properly connect to the database, and
   configure your web server's document root to point to the `web` folder

## TODO
- [ ] Turn off debug mode in production
- [ ] Put secrets (passwords) in a separate file (do not saved in git)
- [ ] Implement authentication and sessions

## Notes API

### Summary
| Method | Path        | Description                |
|--------|-------------|----------------------------|
| GET    | /notes      | Gets the list of all notes |
| POST   | /notes      | Creates a new note         |
| GET    | /notes/{id} | Retrieves the note         |
| PUT    | /notes/{id} | Updates the note           |
| DELETE | /notes/{id} | Deletes the note           |

### GET /notes
Returns a JSON array of all notes. Each note contains the id and
truncated contents of the note (first 40 characters). Notes are
arranged by date in descending order (latest edit first).

### POST /notes/
Creates a new note. Takes a form parameter called `note`, which should
contain the contents of the note. Returns a JSON string with the id
of the newly added note.

### GET /notes/{id}
Retrieves the note with the specified id. Returns a JSON object with
an id, the note itself, and a timestamp of the last edit. Returns 
a 404 if a note with the specified id cannot be found.

### PUT /notes/{id}
Updates the note with the specified id. Takes a form parameter called
"note", which should contain the contents of the note. Returns a 404
if a note with the specified id cannot be found.

### DELETE /notes/{id}
Deletes the note with the specified id. Returns a 404 if a note with
the specified id cannot be found.
