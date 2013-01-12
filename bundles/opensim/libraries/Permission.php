<?php namespace Opensim;

class Permission {

    const PERM_TRANSFER = 0x00002000;
    const PERM_MODIFY   = 0x00004000;
    const PERM_COPY     = 0x00008000;
    const PERM_MOVE     = 0x00080000; 

    // Common masks are:
    const PERM_NONE = 0x00000000;
    const PERM_ALL  = 0x7FFFFFFF;

    // Common values are:

    // no modify: PERM_ALL & ~PERM_MODIFY = 0x7fffbfff
    // no copy: PERM_ALL & ~PERM_COPY = 0x7fff7fff
    // no modify or copy: = 0x7fff3fff
    // no transfer: PERM_ALL & ~PERM_TRANSFER = 0x7fffdfff
    // no modify, no transfer = 0x7fff9fff
    
}