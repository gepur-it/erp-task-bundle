<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since: 18.05.18
 */

namespace GepurIt\ErpTaskBundle\ActionProcessor;

/**
 * Class ActionInterface
 */
interface ActionInterface
{
    /**
     * @return string
     */
    public function getActionId():string;

    /**
     * @return string
     */
    public function getTaskId(): string;

    /**
     * @return string
     */
    public function getTaskType(): string;

    /**
     * @return string
     */
    public function getAuthorId(): string;

    /**
     * @return string[]
     */
    public function getParameters(): array;

    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @param string $message
     * @return void
     */
    public function setMessage(string $message): void;

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime;

    /**
     * ActionInterface constructor.
     *
     * @param string $taskId
     * @param string $type
     * @param string $authorId
     * @param array  $params
     */
    public function __construct(string $taskId, string $type, string $authorId, array $params);


    /**
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    public function getParameter(string $key, $default = null);

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setParameter(string $key, $value): void;

    /**
     * @param array $params
     */
    public function setParameters(array $params): void;

    /**
     * @return string
     */
    public function getName():string;
}
