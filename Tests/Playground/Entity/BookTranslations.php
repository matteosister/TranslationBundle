<?php
/**
 * User: matteo
 * Date: 09/07/12
 * Time: 0.02
 *
 * Just for fun...
 */

namespace Cypress\TranslationBundle\Tests\Playground\Entity;

use Cypress\TranslationBundle\Entity\Base\TranslationEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="book_translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class BookTranslations extends TranslationEntity
{
    /**
     * @var Book
     *
     * @ORM\ManyToOne(targetEntity="Cypress\TranslationBundle\Tests\Playground\Entity\Book", inversedBy="translations")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $object;
}
