<?php
class VenueService
{
    /** Sport types for venue and coach forms. Other modules read them through here. */
    public function sportTypes(): array
    {
        return array_map(
            fn (array $s) => ['id' => (int) $s['id'], 'code' => $s['code'], 'name' => $s['name']],
            (new SportTypeModel())->all()
        );
    }
}
