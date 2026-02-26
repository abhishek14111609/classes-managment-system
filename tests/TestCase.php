<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\CreatesApplication;

/**
 * @method void assertNotNull(mixed $actual, string $message = '')
 * @method void assertSame(mixed $expected, mixed $actual, string $message = '')
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}
