<?php
/**
 * User: matteo
 * Date: 07/07/12
 * Time: 9.31
 * 
 * Just for fun...
 */
 
namespace Cypress\TranslationBundle\Tests\Playground\Entity;

 
/**
 * Book table for testing purpose
 *
 * @Entity
 * @Table(name="book")
 */
class Book
{
    /**
     * @var integer
     *
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Id getter
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
