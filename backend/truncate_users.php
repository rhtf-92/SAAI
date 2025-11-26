<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Schema::disableForeignKeyConstraints();
DB::table('users')->truncate();
DB::table('model_has_roles')->truncate();
Schema::enableForeignKeyConstraints();

echo "Users table truncated.\n";
