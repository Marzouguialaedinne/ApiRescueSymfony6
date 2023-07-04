<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Association;
use App\Entity\WithdrawalPoint;
use App\Exception\EntityNotFoundException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/withdrawal')]
class WithdrawalPointController extends ApiController
{
	protected function relatedRelations($widhrawalPoint)
	{
		/** @var WithdrawalPoint  $widhrawalPoint */
		$address     = $this->entityManager->getRepository(Address::class)->find($widhrawalPoint->getAddressId());

		if (is_null($address)) {
			throw new EntityNotFoundException(static::getRelatedEntity(), (int)$widhrawalPoint->getAddressId());
		}

		$widhrawalPoint->setAddress($address);
		$association = $this->entityManager->getRepository(Association::class)->find($widhrawalPoint->getAssociationId());

		if (is_null($association)) {
			throw new EntityNotFoundException(static::getRelatedEntity(), (int)$widhrawalPoint->getAssociationId());
		}
		$widhrawalPoint->setAssociation($association);

		return $widhrawalPoint;
	}
}
