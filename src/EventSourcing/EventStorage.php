<?php

namespace WebSummerCamp\EventSourcing;

interface EventStorage
{
    public function store(AggregateIdentifier $identifier, array $events): void;

    public function fetch(AggregateIdentifier $identifier): array;
}