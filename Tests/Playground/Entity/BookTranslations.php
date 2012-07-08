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

/**
 * @Entity
 * @Table(name="book_translations",
 *     uniqueConstraints={@UniqueConstraint(name="lookup_unique_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class BookTranslations extends TranslationEntity
{
    /**
     * @var Book
     *
     * @ManyToOne(targetEntity="Vivacom\CmsBundle\Entity\Post", inversedBy="translations")
     * @JoinColumn(onDelete="CASCADE")
     */
    protected $object;
}
