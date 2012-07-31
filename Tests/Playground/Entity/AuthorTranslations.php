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
 * @ORM\Table(name="author_translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class AuthorTranslations extends TranslationEntity
{
    /**
     * @var Author
     *
     * @ORM\ManyToOne(targetEntity="Cypress\TranslationBundle\Tests\Playground\Entity\Author", inversedBy="translations")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $object;
}
