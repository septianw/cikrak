<?php

namespace Sabre\VObject;

use
    DateTime,
    DateTimeZone;

class ExpandFloatingTimesTest extends \PHPUnit_Framework_TestCase {

    function testExpand() {

        $input = <<<ICS
BEGIN:VCALENDAR
VERSION:2.0
BEGIN:VEVENT
UID:foo
DTSTART:20150109T090000
DTEND:20150109T100000
RRULE:FREQ=WEEKLY;INTERVAL=1;UNTIL=20191002T070000Z;BYDAY=FR
END:VEVENT
END:VCALENDAR
ICS;

        $vcal = Reader::read($input);
        $this->assertInstanceOf('Sabre\\VObject\\Component\\VCalendar', $vcal);

        $vcal->expand(new DateTime('2015-01-01'), new DateTime('2015-01-31'));

        $result = $vcal->serialize();

        $output = <<<ICS
BEGIN:VCALENDAR
VERSION:2.0
BEGIN:VEVENT
UID:foo
DTSTART:20150109T090000Z
DTEND:20150109T100000Z
END:VEVENT
BEGIN:VEVENT
UID:foo
DTSTART:20150116T090000Z
DTEND:20150116T100000Z
RECURRENCE-ID:20150116T090000Z
END:VEVENT
BEGIN:VEVENT
UID:foo
DTSTART:20150123T090000Z
DTEND:20150123T100000Z
RECURRENCE-ID:20150123T090000Z
END:VEVENT
BEGIN:VEVENT
UID:foo
DTSTART:20150130T090000Z
DTEND:20150130T100000Z
RECURRENCE-ID:20150130T090000Z
END:VEVENT
END:VCALENDAR

ICS;
        $this->assertEquals($output, str_replace("\r", "", $result));

    }

    function testExpandWithReferenceTimezone() {

        $input = <<<ICS
BEGIN:VCALENDAR
VERSION:2.0
BEGIN:VEVENT
UID:foo
DTSTART:20150109T090000
DTEND:20150109T100000
RRULE:FREQ=WEEKLY;INTERVAL=1;UNTIL=20191002T070000Z;BYDAY=FR
END:VEVENT
END:VCALENDAR
ICS;

        $vcal = Reader::read($input);
        $this->assertInstanceOf('Sabre\\VObject\\Component\\VCalendar', $vcal);

        $vcal->expand(new DateTime('2015-01-01'), new DateTime('2015-01-31'), new \DateTimeZone('Europe/Berlin'));

        $result = $vcal->serialize();

        $output = <<<ICS
BEGIN:VCALENDAR
VERSION:2.0
BEGIN:VEVENT
UID:foo
DTSTART:20150109T080000Z
DTEND:20150109T090000Z
END:VEVENT
BEGIN:VEVENT
UID:foo
DTSTART:20150116T080000Z
DTEND:20150116T090000Z
RECURRENCE-ID:20150116T080000Z
END:VEVENT
BEGIN:VEVENT
UID:foo
DTSTART:20150123T080000Z
DTEND:20150123T090000Z
RECURRENCE-ID:20150123T080000Z
END:VEVENT
BEGIN:VEVENT
UID:foo
DTSTART:20150130T080000Z
DTEND:20150130T090000Z
RECURRENCE-ID:20150130T080000Z
END:VEVENT
END:VCALENDAR

ICS;
        $this->assertEquals($output, str_replace("\r", "", $result));

    }

}
