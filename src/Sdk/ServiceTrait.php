<?php
namespace App\Sdk;

use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\RequestStack;

trait ServiceTrait
{
    use ContainerAwareTrait;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var Logger Logger
     */
    protected $logger;

    /**
     * @var Mailer 
     */
    protected $mailer;

    /**
     * Sets the database connection driver instance
     *
     * @param Connection $connection Instance to the database connection
     */
    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Sets the logger
     *
     * @param Logger $logger Logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Sets the request mailer instance
     *
     * @param Mailer $mailer Instance
     */
    public function setMailer(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Gets the function arguments to be used for logging and other purposes
     *
     * @param string $functionName The function name
     * @param array $arguments The arguments
     */
    public function getArguments($functionName, $arguments)
    {
        $ref = new \ReflectionMethod($this, $functionName);

        $mappedArguments = [];

        foreach ($ref->getParameters() as $key => $parameter) {
            if (!isset($arguments[$key]) && $parameter->isDefaultValueAvailable()) {
                $mappedArguments[$parameter->name] = $parameter->getDefaultValue();
            } else {
                $mappedArguments[$parameter->name] = $arguments[$key];
            }
        }

        return $mappedArguments;
    }

    public function formatStringParameter($parameter)
    {
        $parameter = trim($parameter);
        if ($parameter == "") {
            $parameter = null;
        }
        return $parameter;
    }

    public function formatIntParameter($parameter)
    {
        $parameter = (int)$parameter;
        if ($parameter == 0) {
            $parameter = null;
        }
        return $parameter;
    }

    public function formatBoolParameter($parameter)
    {
        if ($parameter === null) {
            $parameter = null;
        }else{
            $parameter = (bool)$parameter;
        }
        return $parameter;
    }

    public function formatDateParameter($parameter, $isStart = false)
    {
        if (is_null($parameter) || $parameter == "") {
            return null;
        }

        $dateObject = new \DateTime($parameter);

        if ($isStart) {
            $dateObject->setTime(00, 00, 00);
        }else{
            $dateObject->setTime(23, 59, 59);
        }

        return $dateObject;
    }

    public function formatMobileNumber($mobileNumber)
    {
        return substr(preg_replace("/[^0-9]/", "", $mobileNumber), -10);
    }

    public function uId()
    {
        $first5     = substr(md5(microtime()),rand(0,26),5);
        $secondt5   = substr(md5(microtime()),rand(0,26),5);
        $third5     = substr(md5(microtime()),rand(0,26),5);
        $fourth5    = substr(md5(microtime()),rand(0,26),5);

        $result = $first5."-".$secondt5."-".$third5."-".$fourth5;

        return $result;
    }
}