<?php

declare(strict_types=1);

namespace Recipes\RecipeManagement\Tests\Unit\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 */
class BenutzerControllerTest extends UnitTestCase
{
    /**
     * @var \Recipes\RecipeManagement\Controller\BenutzerController|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder($this->buildAccessibleProxy(\Recipes\RecipeManagement\Controller\BenutzerController::class))
            ->onlyMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllBenutzersFromRepositoryAndAssignsThemToView(): void
    {
        $allBenutzers = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $benutzerRepository = $this->getMockBuilder(\Recipes\RecipeManagement\Domain\Repository\BenutzerRepository::class)
            ->onlyMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $benutzerRepository->expects(self::once())->method('findAll')->will(self::returnValue($allBenutzers));
        $this->subject->_set('benutzerRepository', $benutzerRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('benutzers', $allBenutzers);
        $this->subject->_set('view', $view);

        $this->subject->listAction();
    }
}
