create table balers
(
    id         bigint unsigned auto_increment
        primary key,
    name       varchar(255)         not null,
    code       varchar(255)         not null,
    status     tinyint(1) default 1 not null,
    created_at timestamp            null,
    updated_at timestamp            null,
    constraint balers_code_unique
        unique (code)
)
    collate = utf8mb4_unicode_ci;

create table categories
(
    id         bigint unsigned auto_increment
        primary key,
    name       varchar(255)         not null,
    status     tinyint(1) default 1 not null,
    created_at timestamp            null,
    updated_at timestamp            null
)
    collate = utf8mb4_unicode_ci;

create table customers
(
    id                         bigint unsigned auto_increment
        primary key,
    name                       varchar(255)         not null,
    telephone                  varchar(255)         not null,
    alternativeTelephone       varchar(255)         null,
    nameCharge                 varchar(255)         null,
    telephoneCharge            varchar(255)         null,
    alternativeTelephoneCharge varchar(255)         null,
    basePrice                  decimal(10, 2)       not null,
    multiplicater              decimal(10, 2)       not null,
    status                     tinyint(1) default 1 not null,
    created_at                 timestamp            null,
    updated_at                 timestamp            null,
    exchangeRate               decimal(10, 2)       not null,
    type                       tinyint(1)           not null,
    credit                     tinyint(1)           not null,
    prices                     text                 not null
)
    collate = utf8mb4_unicode_ci;

create table departaments
(
    id         bigint unsigned auto_increment
        primary key,
    name       varchar(255) not null,
    created_at timestamp    null,
    updated_at timestamp    null
)
    collate = utf8mb4_unicode_ci;

create table failed_jobs
(
    id         bigint unsigned auto_increment
        primary key,
    uuid       varchar(255)                        not null,
    connection text                                not null,
    queue      text                                not null,
    payload    longtext                            not null,
    exception  longtext                            not null,
    failed_at  timestamp default CURRENT_TIMESTAMP not null,
    constraint failed_jobs_uuid_unique
        unique (uuid)
)
    collate = utf8mb4_unicode_ci;

create table header_packages
(
    id                   varchar(19)       not null
        primary key,
    receive              varchar(255)      not null,
    serie                varchar(255)      not null,
    telephone            varchar(255)      not null,
    city                 varchar(255)      not null,
    alternativeTelephone varchar(255)      null,
    status               tinyint default 2 not null,
    observationStatus    varchar(255)      null,
    callStatus           tinyint default 0 not null,
    observationCall      varchar(255)      null,
    whatsapp             tinyint default 0 not null,
    observationWhatsapp  varchar(255)      null,
    created_at           timestamp         null,
    updated_at           timestamp         null
)
    collate = utf8mb4_unicode_ci;

create table migrations
(
    id        int unsigned auto_increment
        primary key,
    migration varchar(255) not null,
    batch     int          not null
)
    collate = utf8mb4_unicode_ci;

create table office_g_t_s
(
    id         bigint unsigned auto_increment
        primary key,
    name       varchar(255)   not null,
    date       date           not null,
    value      decimal(10, 2) not null,
    created_at timestamp      null,
    updated_at timestamp      null
)
    collate = utf8mb4_unicode_ci;

create table office_u_s_a_s
(
    id              bigint unsigned auto_increment
        primary key,
    name            varchar(255) not null,
    address         varchar(255) null,
    geolocation     varchar(255) null,
    img             varchar(255) null,
    created_at      timestamp    null,
    updated_at      timestamp    null,
    addressManifest varchar(255) null
)
    collate = utf8mb4_unicode_ci;

create table password_resets
(
    email      varchar(255) not null,
    token      varchar(255) not null,
    created_at timestamp    null
)
    collate = utf8mb4_unicode_ci;

create index password_resets_email_index
    on password_resets (email);

create table permits
(
    id         bigint unsigned auto_increment
        primary key,
    name       varchar(255)    not null,
    idParent   bigint unsigned null,
    created_at timestamp       null,
    updated_at timestamp       null,
    constraint permits_idparent_foreign
        foreign key (idParent) references permits (id)
)
    collate = utf8mb4_unicode_ci;

create table personal_access_tokens
(
    id             bigint unsigned auto_increment
        primary key,
    tokenable_type varchar(255)    not null,
    tokenable_id   bigint unsigned not null,
    name           varchar(255)    not null,
    token          varchar(64)     not null,
    abilities      text            null,
    last_used_at   timestamp       null,
    created_at     timestamp       null,
    updated_at     timestamp       null,
    constraint personal_access_tokens_token_unique
        unique (token)
)
    collate = utf8mb4_unicode_ci;

create index personal_access_tokens_tokenable_type_tokenable_id_index
    on personal_access_tokens (tokenable_type, tokenable_id);

create table price_u_s_a_s
(
    id            bigint unsigned auto_increment
        primary key,
    basePrice     decimal(10, 2)       not null,
    multiplicater decimal(10, 2)       not null,
    isDelivery    varchar(255)         not null,
    status        tinyint(1) default 1 not null,
    created_at    timestamp            null,
    updated_at    timestamp            null
)
    collate = utf8mb4_unicode_ci;

create table categories_price_u_s_a_s
(
    id         bigint unsigned auto_increment
        primary key,
    idPriceUSA bigint unsigned not null,
    idCategory bigint unsigned not null,
    created_at timestamp       null,
    updated_at timestamp       null,
    constraint categories_price_u_s_a_s_idcategory_foreign
        foreign key (idCategory) references categories (id),
    constraint categories_price_u_s_a_s_idpriceusa_foreign
        foreign key (idPriceUSA) references price_u_s_a_s (id)
)
    collate = utf8mb4_unicode_ci;

create table departament_price_u_s_a_s
(
    id            bigint unsigned auto_increment
        primary key,
    idPriceUSA    bigint unsigned not null,
    idDepartament bigint unsigned not null,
    created_at    timestamp       null,
    updated_at    timestamp       null,
    constraint departament_price_u_s_a_s_iddepartament_foreign
        foreign key (idDepartament) references departaments (id),
    constraint departament_price_u_s_a_s_idpriceusa_foreign
        foreign key (idPriceUSA) references price_u_s_a_s (id)
)
    collate = utf8mb4_unicode_ci;

create table rols
(
    id         bigint unsigned auto_increment
        primary key,
    name       varchar(255)         not null,
    status     tinyint(1) default 1 not null,
    created_at timestamp            null,
    updated_at timestamp            null
)
    collate = utf8mb4_unicode_ci;

create table rol_permits
(
    id         bigint unsigned auto_increment
        primary key,
    idRol      bigint unsigned   not null,
    idPermit   bigint unsigned   not null,
    status     tinyint default 0 not null,
    created_at timestamp         null,
    updated_at timestamp         null,
    constraint rol_permits_idpermit_foreign
        foreign key (idPermit) references permits (id),
    constraint rol_permits_idrol_foreign
        foreign key (idRol) references rols (id)
)
    collate = utf8mb4_unicode_ci;

create table settings
(
    id         bigint unsigned auto_increment
        primary key,
    name       varchar(255) not null,
    value      text         not null,
    created_at timestamp    null,
    updated_at timestamp    null
)
    collate = utf8mb4_unicode_ci;

create table towns
(
    id            bigint unsigned auto_increment
        primary key,
    name          varchar(255)    not null,
    idDepartament bigint unsigned not null,
    created_at    timestamp       null,
    updated_at    timestamp       null,
    constraint towns_iddepartament_foreign
        foreign key (idDepartament) references departaments (id)
)
    collate = utf8mb4_unicode_ci;

create table travelers
(
    id         bigint unsigned auto_increment
        primary key,
    created_at timestamp null,
    updated_at timestamp null
)
    collate = utf8mb4_unicode_ci;

create table users
(
    id             bigint unsigned auto_increment
        primary key,
    name           varchar(255)         not null,
    user           varchar(255)         not null,
    password       varchar(255)         not null,
    telegramId     varchar(255)         null,
    remember_token varchar(100)         null,
    status         tinyint(1) default 1 not null,
    idRol          bigint unsigned      not null,
    passport       varchar(255)         null,
    created_at     timestamp            null,
    updated_at     timestamp            null,
    constraint users_user_unique
        unique (user),
    constraint users_idrol_foreign
        foreign key (idRol) references rols (id)
)
    collate = utf8mb4_unicode_ci;

create table accounts
(
    id             bigint unsigned auto_increment
        primary key,
    description    varchar(190)                null,
    name           varchar(50)                 not null,
    bank           varchar(50)                 null,
    amount         decimal(10, 2)              not null,
    status         tinyint(1)     default 1    not null,
    sign           varchar(255)   default 'Q'  not null,
    isCashRegister varchar(255)   default '0'  not null,
    inCharge       bigint unsigned             null,
    userCreated    bigint unsigned             not null,
    userUpdated    bigint unsigned             not null,
    created_at     timestamp                   null,
    updated_at     timestamp                   null,
    idCustomer     bigint unsigned             null,
    amountBank     decimal(10, 2) default 0.00 not null,
    numberAccount  varchar(255)                null,
    typeAccount    varchar(255)                null,
    constraint accounts_idcustomer_foreign
        foreign key (idCustomer) references customers (id),
    constraint accounts_incharge_foreign
        foreign key (inCharge) references users (id),
    constraint accounts_usercreated_foreign
        foreign key (userCreated) references users (id),
    constraint accounts_userupdated_foreign
        foreign key (userUpdated) references users (id)
)
    collate = utf8mb4_unicode_ci;

create table collect_g_t_s
(
    id                   bigint unsigned auto_increment
        primary key,
    name                 varchar(255)      not null,
    address              varchar(255)      not null,
    telephone            varchar(255)      not null,
    alternativeTelephone varchar(255)      null,
    date                 date              not null,
    status               tinyint default 2 not null,
    idTown               bigint unsigned   not null,
    userCollect          bigint unsigned   null,
    userCreated          bigint unsigned   not null,
    userUpdated          bigint unsigned   not null,
    created_at           timestamp         null,
    updated_at           timestamp         null,
    constraint collect_g_t_s_idtown_foreign
        foreign key (idTown) references towns (id),
    constraint collect_g_t_s_usercollect_foreign
        foreign key (userCollect) references users (id),
    constraint collect_g_t_s_usercreated_foreign
        foreign key (userCreated) references users (id),
    constraint collect_g_t_s_userupdated_foreign
        foreign key (userUpdated) references users (id)
)
    collate = utf8mb4_unicode_ci;

create table package_u_p_s
(
    id                   varchar(19)                      not null
        primary key,
    code                 bigint                           null,
    guide                varchar(255)                     not null,
    observation          varchar(255)                     null,
    category             varchar(255)                     null,
    departament          varchar(255)                     null,
    type                 varchar(255)                     null,
    office               varchar(255)                     null,
    serie                varchar(255)                     not null,
    receive              varchar(255)                     not null,
    sender               varchar(255)                     not null,
    telephone            varchar(255)                     not null,
    codeUPS              varchar(25)                      null,
    typical              tinyint(1)     default 1         not null,
    typePaymentTypical   tinyint(1)                       null,
    typePayment          tinyint(1)                       null,
    telephoneAlternative varchar(255)                     null,
    idCustomer           bigint unsigned                  null,
    address              varchar(255)                     not null,
    postalCode           varchar(255)                     not null,
    state                varchar(255)                     not null,
    city                 varchar(255)                     not null,
    idAccountPersonal    bigint unsigned                  null,
    totalKg              decimal(10, 2)                   not null,
    totalContent         decimal(10, 2)                   not null,
    weight               decimal(10, 2) default 0.00      not null,
    totalPayment         decimal(10, 2)                   not null,
    balance              decimal(10, 2)                   not null,
    cost                 decimal(10, 2) default 0.00      not null,
    multiplicater        decimal(10, 2) default 0.00      not null,
    updatedMultiplier    tinyint(1)     default 0         not null,
    status               varchar(255)   default 'OFICINA' not null,
    inOffice             datetime                         not null,
    inContent            datetime                         null,
    inAddress            datetime                         null,
    inPayment            datetime                         null,
    inCode               datetime                         null,
    inRoute              datetime                         null,
    inRouteComment       varchar(255)                     null,
    inDelivered          datetime                         null,
    inDeliveredComment   varchar(255)                     null,
    inDeliveredDate      varchar(255)                     null,
    inNull               datetime                         null,
    inNullComment        varchar(255)                     null,
    inStopped            datetime                         null,
    inStoppedComment     varchar(255)                     null,
    inCharge             bigint unsigned                  null,
    userCreated          bigint unsigned                  not null,
    userUpdated          bigint unsigned                  not null,
    idBaler              bigint unsigned                  not null,
    created_at           timestamp                        null,
    updated_at           timestamp                        null,
    constraint package_u_p_s_idaccountpersonal_foreign
        foreign key (idAccountPersonal) references accounts (id),
    constraint package_u_p_s_idbaler_foreign
        foreign key (idBaler) references balers (id),
    constraint package_u_p_s_idcustomer_foreign
        foreign key (idCustomer) references customers (id),
    constraint package_u_p_s_incharge_foreign
        foreign key (inCharge) references users (id),
    constraint package_u_p_s_usercreated_foreign
        foreign key (userCreated) references users (id),
    constraint package_u_p_s_userupdated_foreign
        foreign key (userUpdated) references users (id)
)
    collate = utf8mb4_unicode_ci;

create table account_collections
(
    id           bigint unsigned auto_increment
        primary key,
    exchangeRate decimal(10, 2)                   not null,
    amountDollar decimal(10, 2)                   not null,
    amount       decimal(10, 2)                   not null,
    bank         varchar(255)                     null,
    keyNumber    varchar(255)                     null,
    nameSend     varchar(255)                     null,
    collect      varchar(255) default 'PENDIENTE' not null,
    inCollect    datetime                         null,
    type         varchar(255) default 'DEPÓSITO'  not null,
    inType       datetime                         null,
    idPackageUPS varchar(255)                     not null,
    idAccount    bigint unsigned                  not null,
    userCreated  bigint unsigned                  not null,
    userUpdated  bigint unsigned                  not null,
    created_at   timestamp                        null,
    updated_at   timestamp                        null,
    constraint account_collections_idaccount_foreign
        foreign key (idAccount) references accounts (id),
    constraint account_collections_idpackageups_foreign
        foreign key (idPackageUPS) references package_u_p_s (id),
    constraint account_collections_usercreated_foreign
        foreign key (userCreated) references users (id),
    constraint account_collections_userupdated_foreign
        foreign key (userUpdated) references users (id)
)
    collate = utf8mb4_unicode_ci;

create table box_u_p_s
(
    id         bigint unsigned auto_increment
        primary key,
    weightKg   decimal(10, 2) not null,
    idPackage  varchar(255)   not null,
    created_at timestamp      null,
    updated_at timestamp      null,
    constraint box_u_p_s_idpackage_foreign
        foreign key (idPackage) references package_u_p_s (id)
)
    collate = utf8mb4_unicode_ci;

create table content_u_p_s
(
    id         bigint unsigned auto_increment
        primary key,
    quantity   decimal(10, 2) not null,
    content    varchar(255)   not null,
    content_en varchar(255)   not null,
    price      decimal(10, 2) not null,
    subtotal   decimal(10, 2) not null,
    idPackage  varchar(255)   not null,
    created_at timestamp      null,
    updated_at timestamp      null,
    constraint content_u_p_s_idpackage_foreign
        foreign key (idPackage) references package_u_p_s (id)
)
    collate = utf8mb4_unicode_ci;

create table package_u_p_s_customers
(
    id                 bigint unsigned auto_increment
        primary key,
    idPackageUPS       varchar(255)    not null,
    idCustomer         bigint unsigned not null,
    idAccountPersonal  bigint unsigned null,
    weight             decimal(10, 2)  not null,
    subtotal           decimal(10, 2)  not null,
    balance            decimal(10, 2)  not null,
    cost               decimal(10, 2)  not null,
    multiplicater      decimal(10, 2)  not null,
    updatedMultiplier  tinyint(1)      not null,
    typePaymentTypical tinyint(1)      not null,
    created_at         timestamp       null,
    updated_at         timestamp       null,
    constraint package_u_p_s_customers_idaccountpersonal_foreign
        foreign key (idAccountPersonal) references accounts (id),
    constraint package_u_p_s_customers_idcustomer_foreign
        foreign key (idCustomer) references customers (id),
    constraint package_u_p_s_customers_idpackageups_foreign
        foreign key (idPackageUPS) references package_u_p_s (id)
)
    collate = utf8mb4_unicode_ci;

create table shipment_u_s_a_s
(
    id         bigint unsigned auto_increment
        primary key,
    status     tinyint(1) default 1 not null,
    created_at timestamp            null,
    updated_at timestamp            null,
    city       varchar(255)         not null,
    date       date                 not null,
    inCharge   bigint unsigned      not null,
    constraint shipment_u_s_a_s_incharge_foreign
        foreign key (inCharge) references users (id)
)
    collate = utf8mb4_unicode_ci;

create table bags
(
    id           bigint unsigned auto_increment
        primary key,
    created_at   timestamp         null,
    updated_at   timestamp         null,
    bag          varchar(255)      not null,
    capacity     varchar(255)      not null,
    idShipment   bigint unsigned   not null,
    userTraveler bigint unsigned   not null,
    status       tinyint default 2 not null,
    constraint bags_idshipment_foreign
        foreign key (idShipment) references shipment_u_s_a_s (id),
    constraint bags_usertraveler_foreign
        foreign key (userTraveler) references users (id)
)
    collate = utf8mb4_unicode_ci;

create table packages
(
    id                bigint unsigned auto_increment
        primary key,
    send              varchar(255)      not null,
    departament       varchar(255)      not null,
    type              varchar(255)      not null,
    content           varchar(255)      not null,
    content_en        varchar(255)      not null,
    updatedMultiplier tinyint(1)        not null,
    multiplier        decimal(10, 2)    not null,
    category          varchar(255)      not null,
    weight            varchar(255)      not null,
    cost              varchar(255)      not null,
    payment           varchar(255)      not null,
    guide             varchar(255)      not null,
    observation       varchar(255)      null,
    subtotal          varchar(255)      not null,
    office            varchar(255)      null,
    idBaler           bigint unsigned   not null,
    idBag             bigint unsigned   null,
    idHeaderPackage   varchar(255)      not null,
    idParent          int               null,
    status            tinyint default 2 not null,
    created_at        timestamp         null,
    updated_at        timestamp         null,
    inBag             tinyint default 1 not null,
    correlative       int               not null,
    delivered         tinyint default 1 not null,
    codeFast          varchar(255)      null,
    constraint packages_idbag_foreign
        foreign key (idBag) references bags (id),
    constraint packages_idbaler_foreign
        foreign key (idBaler) references balers (id),
    constraint packages_idheaderpackage_foreign
        foreign key (idHeaderPackage) references header_packages (id)
)
    collate = utf8mb4_unicode_ci;

create table transactions
(
    id          bigint unsigned auto_increment
        primary key,
    description varchar(255)      not null,
    amount      decimal(10, 2)    not null,
    status      tinyint default 2 not null,
    type        tinyint(1)        not null,
    idaccount   bigint unsigned   not null,
    userCreated bigint unsigned   not null,
    reference   varchar(255)      null,
    created_at  timestamp         null,
    updated_at  timestamp         null,
    constraint transactions_idaccount_foreign
        foreign key (idaccount) references accounts (id),
    constraint transactions_usercreated_foreign
        foreign key (userCreated) references users (id)
)
    collate = utf8mb4_unicode_ci;


