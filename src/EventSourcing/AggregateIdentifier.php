<?php

namespace WebSummerCamp\EventSourcing;

interface AggregateIdentifier
{
    public function toString(): string;
}