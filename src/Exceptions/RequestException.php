<?php namespace Vindi\Exceptions;

use Exception;

/**
 * Class RequestException
 *
 * @package Vindi\Exceptions
 */
class RequestException extends Exception
{
    /**
     * @var mixed
     */
    protected $errors;

    /**
     * @var array
     */
    protected $ids;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var array
     */
    protected $messages;

    /**
     * @var array
     **/
    private $lastOptions;

    /**
     * ValidationException constructor.
     *
     * @param int   $status
     * @param mixed $errors
     */
    public function __construct($status, $errors, array $lastOptions = [])
    {
        $this->lastOptions = $lastOptions;
        $this->errors      = $errors;
        $this->code        = $status;

        $this->ids        = [];
        $this->parameters = [];
        $this->messages   = [];

        foreach ($errors as $error) {
            $this->ids[] = $error->id;
            if (isset($error->parameter)) {
                $this->parameters[] = $error->parameter;
            }

            /* Novas mensagens de retorno */
            
            if($error->parameter === 'card_number_first_six'){
                $this->messages[] = 'O número do cartão não possui o tamanho de no mínimo 6 digitos esperados.';
            }elseif($error->parameter === 'card_number_last_four'){
                $this->messages[] = 'Os 4 últimos digitos do cartão não podem ser vázios.';
            }elseif($error->parameter === 'card_number'){
                $this->messages[] = 'Número de cartão inválido ou a bandeira não é válida para este cartão.';
            }elseif($error->parameter === 'card_expiration'){
                $this->messages[] = 'A data de válidade do cartão não é válida.';
            }elseif($error->parameter === 'card_expiration'){
                $this->messages[] = 'A data de válidade do cartão não é válida.';
            }elseif($error->parameter === 'card_cvv'){
                $this->messages[] = 'O código de segurança do cartão não é válido.';
            }elseif($error->parameter === 'payment_company_code'){
                $this->messages[] = 'Essa bandeira de cartão não existe ou não é aceita.';
            }elseif($error->parameter === 'payment_company_id'){
                $this->messages[] = 'A bandeira do cartão não pode ficar em branco.';
            }elseif($error->parameter === 'email'){
                $this->messages[] = 'O e-mail enviado não é válido.';
            }elseif($error->parameter === 'holder_name'){
                $this->messages[] = 'O nome impresso no cartão não pode ser vazio.';
            }elseif($error->parameter === 'name'){
                $this->messages[] = 'O nome do assinante não pode ser vazio.';
            }else{
                $this->messages[] = $error->message;
            }
        }

        $this->message = array_unique($this->messages);
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function getIds()
    {
        return $this->ids;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Return the last request body
     * @return string
     **/
    public function getRequestBody()
    {
        return json_encode($this->lastOptions['json']);
    }
}
