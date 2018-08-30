<?php

namespace WebSummerCamp\EventSourcing;

interface AggregateRoot
{
    public function getIdentifier(): AggregateIdentifier;

    public function getRecordedEvents(): RecordedEvents;
}