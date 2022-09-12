<?php

namespace App\EntityFactory;

use App\Entity\Contact;

class ContactFactory
{
    public static function create(array $data): Contact
    {
        $contact = new Contact();

        self::setAttributes($contact, $data);

        return $contact;
    }

    public static function setAttributes(Contact $contact, array $data) {
        $contact->setFirstName($data['first_name'] ?? null);
        $contact->setLastName($data['last_name'] ?? null);
        $contact->setPhone($data['phone'] ?? null);
        $contact->setEmail($data['email'] ?? null);
        if(!empty($data['birthday'])) {
            $birthday = \DateTime::createFromFormat('Y-m-d', $data['birthday']);
            $contact->setBirthday($birthday);
        }
        $contact->setAddress($data['address'] ?? null);
        $contact->setPicture($data['picture'] ?? null);
    }
}
