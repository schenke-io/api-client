<?php

namespace SchenkeIo\ApiClient;



abstract class BaseJsonClient extends BaseClient
{
    protected ApiType $type = ApiType::JSON;
}
