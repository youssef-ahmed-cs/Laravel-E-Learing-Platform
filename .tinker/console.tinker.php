<?php #Tinker
use App\Models\{Enrollment, User, Course , Profile};

return User::cursorPaginate(100)->items();
