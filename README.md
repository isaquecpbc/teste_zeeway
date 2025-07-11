# Teste Técnico - Desenvolvedor PHP + Laravel para Zeeway.
Pequena aplicação REST API com laravel 10;

## Backend:


### Para iniciar o laravel com o docker:


1. na raiz do projeto, ``` $ cd zeeway-api ```
2. ``` $ docker compose up -d ```
3. ``` $ docker compose exec zeeway_api bash ```
4. ``` $ cd zeeway-app ```
5. ``` $ composer update ```
6. ``` $ php artisan key:generate ```
7. ``` $ php artisan jwt:secret ```
8. ``` $ php artisan optimize:clear ```



#### Para popular o banco de dados:

1. na raiz do projeto, ``` $ cd zeeway-api ```
2. ``` $ docker compose exec zeeway_api bash  ```
3. ``` $ cd zeeway-app ```
4. ``` $ php artisan migrate:fresh --seed ```


### Teste o acesso da api em http://localhost/


#### Para gerar e visualizar o Swagger:
1. ``` $ docker compose exec zeeway_api bash ```
2. ``` $ cd zeeway-app ```
3. ``` $ php artisan l5-swagger:generate ```
4. Acesse a documentação no navegador: ``` http://localhost/api/documentation ```
5. Faça o login e salve o token no botão: ``` Authorize ```


## Esta disponível o arquivo para api no postman na raiz do projeto!

---

# Melhorias & Futuras Atualizações Levantadas

Apesar de a aplicação estar funcional, sempre há espaço para melhorias. Aqui estão algumas questões levantadas para expandir e otimizar o projeto:

### Funcionalidades

1. **Tasks:**
   - Implementar rota de check para dar update na task com status done passando apenas o ID da task.

2. **Soft deletes:**
   - Implementar melhoria em registros deletados.
   - Registrar logs de deletes.

### Backend

1. **Autenticação e Autorização:**
   - Implementar políticas de autorização mais finas usando `Laravel Gates` ou `Policies` para maior controle de acesso.

2. **Testes Automatizados:**
   - Ampliar a cobertura de testes automatizados incluindo testes de integração e de interface (API).
   - Considerar o uso de mocks e fakes para testes mais rápidos e independentes do banco de dados.

3. **Logs e Monitoramento:**
   - Implementar ferramentas de logs e monitoramento de desempenho como `Sentry` ou `Loggly` para capturar erros em produção.

4. **Escalabilidade:**
   - Considerar cacheamento das respostas frequentes com `Redis` ou `Memcached`.

Estas melhorias ajudariam a criar uma aplicação ainda mais robusta e mais segura.