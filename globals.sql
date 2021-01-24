-- auto-generated definition
CREATE TABLE public.iyzico
(
    id SERIAL NOT NULL CONSTRAINT iyzico_pk PRIMARY KEY,
    iyzico_api_key TEXT DEFAULT 'IYZICO_API_KEY'::TEXT NOT NULL,
    iyzico_secret_key TEXT DEFAULT 'IYZICO_SECRET_KEY'::TEXT NOT NULL,
    iyzico_base_url   TEXT DEFAULT 'IYZICO_BASE_URL'::TEXT NOT NULL
);

CREATE UNIQUE INDEX iyzico_id_uindex ON iyzico (id);

CREATE TABLE public.address (
    id integer NOT NULL,
    address_name character varying NOT NULL,
    full_name character varying NOT NULL,
    address character varying NOT NULL,
    county character varying NOT NULL,
    city character varying NOT NULL,
    mobile character varying NOT NULL
);

CREATE SEQUENCE public.address_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE public.admin_account (
    id integer NOT NULL,
    name character varying NOT NULL,
    surname character varying NOT NULL,
    email character varying NOT NULL,
    password character varying NOT NULL,
    is_deleted boolean DEFAULT false NOT NULL,
    mobile character varying NOT NULL
);

CREATE SEQUENCE public.admin_account_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE public.admin_account_profile (
    id integer NOT NULL,
    admin_account_id integer NOT NULL,
    admin_profile_id integer NOT NULL
);

CREATE SEQUENCE public.admin_account_profile_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE public.admin_permission (
    id integer NOT NULL,
    name character varying NOT NULL,
    slug character varying NOT NULL
);

CREATE SEQUENCE public.admin_permission_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE public.admin_profile (
    id integer NOT NULL,
    name character varying NOT NULL,
    is_deleted boolean DEFAULT false NOT NULL
);

CREATE SEQUENCE public.admin_profile_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE public.admin_profile_permission (
    id integer NOT NULL,
    admin_profile_id integer NOT NULL,
    admin_permission_id integer NOT NULL
);

CREATE SEQUENCE public.admin_profile_permission_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE public.agreements_strings (
    about_us character varying NOT NULL,
    sign_up_agreement character varying NOT NULL,
    terms_of_use character varying NOT NULL,
    confidentiality_agreement character varying NOT NULL,
    distant_sales_agreement character varying NOT NULL,
    deliverables character varying NOT NULL,
    cancel_refund_change character varying
);

CREATE TABLE public.bank_accounts (
    id integer NOT NULL,
    name character varying NOT NULL,
    logo character varying NOT NULL,
    country character varying,
    branch_name character varying,
    currency character varying,
    city character varying,
    branch_code character varying,
    account_owner character varying,
    account_number character varying,
    iban character varying
);

CREATE SEQUENCE public.bank_account_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE public.banner (
    id integer NOT NULL,
    pic character varying NOT NULL,
    number_of_show integer DEFAULT 0 NOT NULL,
    name character varying NOT NULL
);

CREATE SEQUENCE public.banner_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE public.cargo_company (
    id integer NOT NULL,
    name character varying NOT NULL
);

CREATE SEQUENCE public.cargo_company_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE public.cart (
    id integer NOT NULL,
    cart_no integer,
    user_account_id integer NOT NULL,
    product_id integer NOT NULL,
    variant_id integer NOT NULL,
    quantity integer DEFAULT 1 NOT NULL,
    cargo_company_id integer,
    payment_method character varying,
    installment character varying,
    billing_address_id integer,
    shipping_address_id integer
);

CREATE SEQUENCE public.cart_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE public.category (
    id integer NOT NULL,
    slug character varying NOT NULL,
    name character varying NOT NULL,
    is_deleted boolean DEFAULT false NOT NULL
);

CREATE SEQUENCE public.category_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE public.category_menu (
    id integer NOT NULL,
    category_id integer NOT NULL,
    menu_id integer NOT NULL
);

CREATE SEQUENCE public.category_menu_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE public.faq (
    id integer NOT NULL,
    question character varying NOT NULL,
    answer character varying NOT NULL
);

CREATE SEQUENCE public.faq_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE public.menu (
    id integer NOT NULL,
    name character varying NOT NULL,
    slug character varying NOT NULL,
    is_deleted boolean DEFAULT false NOT NULL
);

CREATE SEQUENCE public.menu_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE public.order_notice (
    id integer NOT NULL,
    name character varying NOT NULL,
    email character varying NOT NULL,
    mobile character varying NOT NULL,
    message character varying NOT NULL,
    is_deleted boolean DEFAULT false NOT NULL,
    is_approved boolean DEFAULT false NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    bank_id integer NOT NULL
);

CREATE SEQUENCE public.order_notice_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE public.product (
    id integer NOT NULL,
    name character varying NOT NULL,
    price real NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    is_deleted boolean DEFAULT false NOT NULL,
    tax integer NOT NULL,
    description text NOT NULL,
    variant_title character varying NOT NULL,
    cargo_price double precision NOT NULL,
    view integer DEFAULT 0 NOT NULL
);

CREATE TABLE public.product_category (
    id integer NOT NULL,
    product_id integer NOT NULL,
    category_id integer NOT NULL
);

CREATE SEQUENCE public.product_category_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE public.product_comment (
    id integer NOT NULL,
    product_id integer NOT NULL,
    user_id integer NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    ip_address character varying NOT NULL,
    comment text NOT NULL,
    rate integer NOT NULL,
    is_deleted boolean DEFAULT false NOT NULL
);

CREATE SEQUENCE public.product_comment_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE SEQUENCE public.product_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE public.product_photo (
    id integer NOT NULL,
    product_id integer NOT NULL,
    path character varying,
    is_deleted boolean DEFAULT false NOT NULL
);

CREATE SEQUENCE public.product_photo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE public.product_variant (
    id integer NOT NULL,
    name character varying NOT NULL,
    stock integer NOT NULL,
    product_id integer NOT NULL
);

CREATE SEQUENCE public.product_variant_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE public.settings (
    id integer NOT NULL,
    is_deleted boolean DEFAULT false NOT NULL,
    name character varying NOT NULL,
    title character varying NOT NULL,
    description character varying NOT NULL,
    keywords character varying NOT NULL,
    copyright character varying NOT NULL,
    mail character varying NOT NULL,
    link character varying NOT NULL,
    address character varying NOT NULL,
    phone character varying NOT NULL,
    footer_text character varying NOT NULL,
    facebook character varying DEFAULT NULL,
    instagram character varying DEFAULT NULL,
    linkedin character varying DEFAULT NULL,
    twitter character varying DEFAULT NULL,
    youtube character varying DEFAULT NULL,
    pinterest character varying DEFAULT NULL
);

CREATE SEQUENCE public.settings_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE public.user_account (
    id integer NOT NULL,
    email character varying NOT NULL,
    is_deleted boolean DEFAULT false NOT NULL,
    mobile character varying,
    password character varying NOT NULL,
    activation_code character varying,
    ip_address character varying,
    name character varying NOT NULL,
    is_email_approved boolean DEFAULT false NOT NULL,
    is_mobile_approved boolean DEFAULT false NOT NULL,
    is_unsubscribe boolean DEFAULT false NOT NULL,
    created_at character varying DEFAULT now() NOT NULL
);

CREATE TABLE public.user_account_address (
    id integer NOT NULL,
    user_account_id integer NOT NULL,
    address_id integer NOT NULL
);

CREATE SEQUENCE public.user_account_address_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE public.user_account_favorite (
    id integer NOT NULL,
    user_account_id integer NOT NULL,
    product_id integer NOT NULL
);

CREATE SEQUENCE public.user_account_favorite_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE SEQUENCE public.user_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER TABLE ONLY public.address ALTER COLUMN id SET DEFAULT nextval('public.address_id_seq'::regclass);
ALTER TABLE ONLY public.admin_account ALTER COLUMN id SET DEFAULT nextval('public.admin_account_id_seq'::regclass);
ALTER TABLE ONLY public.admin_account_profile ALTER COLUMN id SET DEFAULT nextval('public.admin_account_profile_id_seq'::regclass);
ALTER TABLE ONLY public.admin_permission ALTER COLUMN id SET DEFAULT nextval('public.admin_permission_id_seq'::regclass);
ALTER TABLE ONLY public.admin_profile ALTER COLUMN id SET DEFAULT nextval('public.admin_profile_id_seq'::regclass);
ALTER TABLE ONLY public.admin_profile_permission ALTER COLUMN id SET DEFAULT nextval('public.admin_profile_permission_id_seq'::regclass);
ALTER TABLE ONLY public.bank_accounts ALTER COLUMN id SET DEFAULT nextval('public.bank_account_id_seq'::regclass);
ALTER TABLE ONLY public.banner ALTER COLUMN id SET DEFAULT nextval('public.banner_id_seq'::regclass);
ALTER TABLE ONLY public.cargo_company ALTER COLUMN id SET DEFAULT nextval('public.cargo_company_id_seq'::regclass);
ALTER TABLE ONLY public.cart ALTER COLUMN id SET DEFAULT nextval('public.cart_id_seq'::regclass);
ALTER TABLE ONLY public.category ALTER COLUMN id SET DEFAULT nextval('public.category_id_seq'::regclass);
ALTER TABLE ONLY public.category_menu ALTER COLUMN id SET DEFAULT nextval('public.category_menu_id_seq'::regclass);
ALTER TABLE ONLY public.faq ALTER COLUMN id SET DEFAULT nextval('public.faq_id_seq'::regclass);
ALTER TABLE ONLY public.menu ALTER COLUMN id SET DEFAULT nextval('public.menu_id_seq'::regclass);
ALTER TABLE ONLY public.order_notice ALTER COLUMN id SET DEFAULT nextval('public.order_notice_id_seq'::regclass);
ALTER TABLE ONLY public.product ALTER COLUMN id SET DEFAULT nextval('public.product_id_seq'::regclass);
ALTER TABLE ONLY public.product_category ALTER COLUMN id SET DEFAULT nextval('public.product_category_id_seq'::regclass);
ALTER TABLE ONLY public.product_comment ALTER COLUMN id SET DEFAULT nextval('public.product_comment_id_seq'::regclass);
ALTER TABLE ONLY public.product_photo ALTER COLUMN id SET DEFAULT nextval('public.product_photo_id_seq'::regclass);
ALTER TABLE ONLY public.product_variant ALTER COLUMN id SET DEFAULT nextval('public.product_variant_id_seq'::regclass);
ALTER TABLE ONLY public.settings ALTER COLUMN id SET DEFAULT nextval('public.settings_id_seq'::regclass);
ALTER TABLE ONLY public.user_account ALTER COLUMN id SET DEFAULT nextval('public.user_id_seq'::regclass);
ALTER TABLE ONLY public.user_account_address ALTER COLUMN id SET DEFAULT nextval('public.user_account_address_id_seq'::regclass);
ALTER TABLE ONLY public.user_account_favorite ALTER COLUMN id SET DEFAULT nextval('public.user_account_favorite_id_seq'::regclass);

INSERT INTO public.admin_account (id, name, surname, email, password, is_deleted, mobile) VALUES (1, 'Default', 'Admin', ':admin_email', ':admin_password', false, '5999999999');
INSERT INTO public.admin_account_profile (id, admin_account_id, admin_profile_id) VALUES (1, 1, 1);

INSERT INTO public.admin_permission (id, name, slug) VALUES (1, 'Dashboard Görüntüle', 'site_dashboard_show');
INSERT INTO public.admin_permission (id, name, slug) VALUES (2, 'Havale Bildirimleri Listesi', 'bank_transfer_list');
INSERT INTO public.admin_permission (id, name, slug) VALUES (3, 'Site Genel Ayarlar', 'settings_general');
INSERT INTO public.admin_permission (id, name, slug) VALUES (4, 'Metin Ayarları', 'settings_strings');
INSERT INTO public.admin_permission (id, name, slug) VALUES (5, 'Banka Ayarları', 'settings_bank');
INSERT INTO public.admin_permission (id, name, slug) VALUES (6, 'Sık Sorulan Sorular Listesi', 'faq_list');
INSERT INTO public.admin_permission (id, name, slug) VALUES (7, 'Logo Ayarları', 'settings_logo');
INSERT INTO public.admin_permission (id, name, slug) VALUES (8, 'Banner Listesi', 'all_banners');
INSERT INTO public.admin_permission (id, name, slug) VALUES (9, 'Kargo Listesi', 'cargo_list');
INSERT INTO public.admin_permission (id, name, slug) VALUES (10, 'Admin Hesap Listesi', 'account_list_show');
INSERT INTO public.admin_permission (id, name, slug) VALUES (11, 'Admin Profil Listesi', 'profile_list_show');
INSERT INTO public.admin_permission (id, name, slug) VALUES (12, 'Ürün Oluştur', 'product_create');
INSERT INTO public.admin_permission (id, name, slug) VALUES (13, 'Ürün Listesi', 'product_list');
INSERT INTO public.admin_permission (id, name, slug) VALUES (14, 'Menü Listesi', 'menu_list');
INSERT INTO public.admin_permission (id, name, slug) VALUES (15, 'Site Genel Ayarları Güncelle', 'settings_general_update');
INSERT INTO public.admin_permission (id, name, slug) VALUES (16, 'Metin Ayarları Güncelle', 'settings_strings_update');
INSERT INTO public.admin_permission (id, name, slug) VALUES (17, 'Havale Bildirimi Güncelle', 'bank_transfer_update');
INSERT INTO public.admin_permission (id, name, slug) VALUES (18, 'Havale Bildirimi Silme', 'bank_transfer_delete');
INSERT INTO public.admin_permission (id, name, slug) VALUES (19, 'Havale Bildirimi Silmeyi Geri Alma', 'bank_transfer_undelete');
INSERT INTO public.admin_permission (id, name, slug) VALUES (20, 'Banka Oluştur', 'settings_bank_delete');
INSERT INTO public.admin_permission (id, name, slug) VALUES (21, 'Banka Güncelle', 'settings_bank_update');
INSERT INTO public.admin_permission (id, name, slug) VALUES (22, 'Sık Sorulan Sorular Güncelle', 'faq_update');
INSERT INTO public.admin_permission (id, name, slug) VALUES (23, 'Sık Sorulan Sorular Sil', 'faq_delete');
INSERT INTO public.admin_permission (id, name, slug) VALUES (24, 'Logo Update', 'update_logo');
INSERT INTO public.admin_permission (id, name, slug) VALUES (25, 'Banner Oluştur', 'create_banner');
INSERT INTO public.admin_permission (id, name, slug) VALUES (26, 'Banner Güncelle', 'update_banner');
INSERT INTO public.admin_permission (id, name, slug) VALUES (27, 'Banner Sil', 'delete_banner');
INSERT INTO public.admin_permission (id, name, slug) VALUES (28, 'Banka Hesabı Oluştur', 'settings_bank_create');
INSERT INTO public.admin_permission (id, name, slug) VALUES (29, 'Sık Sorulan Soru Oluştur', 'faq_create');
INSERT INTO public.admin_permission (id, name, slug) VALUES (30, 'Kargo Firması Oluştur', 'cargo_create');
INSERT INTO public.admin_permission (id, name, slug) VALUES (31, 'Kargo Firması Güncelle', 'cargo_update');
INSERT INTO public.admin_permission (id, name, slug) VALUES (32, 'Kargo Firması Sil', 'cargo_delete');
INSERT INTO public.admin_permission (id, name, slug) VALUES (33, 'Admin Hesabı Oluştur', 'account_create');
INSERT INTO public.admin_permission (id, name, slug) VALUES (34, 'Admin Hesap Detayı', 'account_detail_show');
INSERT INTO public.admin_permission (id, name, slug) VALUES (35, 'Hesap Güncelle', 'account_update');
INSERT INTO public.admin_permission (id, name, slug) VALUES (36, 'Hesap Sil', 'account_delete');
INSERT INTO public.admin_permission (id, name, slug) VALUES (37, 'Profil Oluştur', 'profile_create');
INSERT INTO public.admin_permission (id, name, slug) VALUES (38, 'Profil Detayı', 'profile_detail_show');
INSERT INTO public.admin_permission (id, name, slug) VALUES (39, 'Profil Güncelle', 'profile_update');
INSERT INTO public.admin_permission (id, name, slug) VALUES (40, 'Profil Sil', 'profile_delete');
INSERT INTO public.admin_permission (id, name, slug) VALUES (41, 'Menu Detay', 'menu_detail');
INSERT INTO public.admin_permission (id, name, slug) VALUES (42, 'Menu Oluştur', 'menu_create');
INSERT INTO public.admin_permission (id, name, slug) VALUES (43, 'Menu Güncelle', 'menu_update');
INSERT INTO public.admin_permission (id, name, slug) VALUES (44, 'Menu Sil', 'menu_delete');
INSERT INTO public.admin_permission (id, name, slug) VALUES (45, 'Kategori Oluştur', 'category_create');
INSERT INTO public.admin_permission (id, name, slug) VALUES (46, 'Ürün Sil', 'product_delete');
INSERT INTO public.admin_permission (id, name, slug) VALUES (47, 'Ürün Silmeyi Geri Al', 'product_undelete');
INSERT INTO public.admin_permission (id, name, slug) VALUES (48, 'Ürün Detayı', 'product_detail');
INSERT INTO public.admin_permission (id, name, slug) VALUES (49, 'Ürün Fotoğrafı Silme', 'product_img_delete');
INSERT INTO public.admin_permission (id, name, slug) VALUES (50, 'Ürün Güncelle', 'product_update');
INSERT INTO public.admin_permission (id, name, slug) VALUES (51, 'Iyzico Ayarları Görüntüleme', 'iyzico_settings_show');
INSERT INTO public.admin_permission (id, name, slug) VALUES (52, 'Sipariş Listesi', 'order_list');
INSERT INTO public.admin_permission (id, name, slug) VALUES (53, 'Iyzico Ayarları Güncelleme', 'iyzico_settings_update');
INSERT INTO public.admin_permission (id, name, slug) VALUES (54, 'Sipariş Detayı', 'order_detail');
INSERT INTO public.admin_permission (id, name, slug) VALUES (55, 'Siparişi Onayla', 'approve_the_order');
INSERT INTO public.admin_permission (id, name, slug) VALUES (56, 'Siparişi Kargola', 'ship_the_order');
INSERT INTO public.admin_permission (id, name, slug) VALUES (57, 'Kullanıcı Listesi', 'user_list');

INSERT INTO public.admin_profile (id, name, is_deleted) VALUES (1, 'GM', false);

INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (1, 1, 1);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (2, 1, 2);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (3, 1, 3);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (4, 1, 4);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (5, 1, 5);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (6, 1, 6);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (7, 1, 7);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (8, 1, 8);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (9, 1, 9);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (10, 1, 10);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (11, 1, 11);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (12, 1, 12);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (13, 1, 13);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (14, 1, 14);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (15, 1, 15);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (16, 1, 16);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (17, 1, 17);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (18, 1, 18);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (19, 1, 19);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (20, 1, 20);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (21, 1, 21);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (22, 1, 22);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (23, 1, 23);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (24, 1, 24);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (25, 1, 25);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (26, 1, 26);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (27, 1, 27);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (28, 1, 28);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (29, 1, 29);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (30, 1, 30);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (31, 1, 31);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (32, 1, 32);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (33, 1, 33);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (34, 1, 34);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (35, 1, 35);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (36, 1, 36);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (37, 1, 37);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (38, 1, 38);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (39, 1, 39);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (40, 1, 40);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (41, 1, 41);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (42, 1, 42);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (43, 1, 43);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (44, 1, 44);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (45, 1, 45);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (46, 1, 46);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (47, 1, 47);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (48, 1, 48);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (49, 1, 49);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (50, 1, 50);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (51, 1, 51);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (52, 1, 52);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (53, 1, 53);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (54, 1, 54);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (55, 1, 55);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (56, 1, 56);
INSERT INTO public.admin_profile_permission (id, admin_profile_id, admin_permission_id) VALUES (57, 1, 57);
INSERT INTO public.agreements_strings (about_us, sign_up_agreement, terms_of_use, confidentiality_agreement, distant_sales_agreement, deliverables, cancel_refund_change) VALUES ('HAKKIMIZDA

Until recently, the prevailing view assumed lorem ipsum was born as a nonsense text. “It''s not Latin, though it looks like it, and it actually says nothing,” Before & After magazine answered a curious reader, “Its ‘words’ loosely approximate the frequency with which letters occur in English, which is why at a glance it looks pretty real.”

As Cicero would put it, “Um, not so fast.”

The placeholder text, beginning with the line “Lorem ipsum dolor sit amet, consectetur adipiscing elit”, looks like Latin because in its youth, centuries ago, it was Latin.

Richard McClintock, a Latin scholar from Hampden-Sydney College, is credited with discovering the source behind the ubiquitous filler text. In seeing a sample of lorem ipsum, his interest was piqued by consectetur—a genuine, albeit rare, Latin word. Consulting a Latin dictionary led McClintock to a passage from De Finibus Bonorum et Malorum (“On the Extremes of Good and Evil”), a first-century B.C. text from the Roman philosopher Cicero.

In particular, the garbled words of lorem ipsum bear an unmistakable resemblance to sections 1.10.32–33 of Cicero''s work, with the most notable passage excerpted below:', 'KAYIT SÖZLEŞMESİ

Until recently, the prevailing view assumed lorem ipsum was born as a nonsense text. “It''s not Latin, though it looks like it, and it actually says nothing,” Before & After magazine answered a curious reader, “Its ‘words’ loosely approximate the frequency with which letters occur in English, which is why at a glance it looks pretty real.”

As Cicero would put it, “Um, not so fast.”

The placeholder text, beginning with the line “Lorem ipsum dolor sit amet, consectetur adipiscing elit”, looks like Latin because in its youth, centuries ago, it was Latin.

Richard McClintock, a Latin scholar from Hampden-Sydney College, is credited with discovering the source behind the ubiquitous filler text. In seeing a sample of lorem ipsum, his interest was piqued by consectetur—a genuine, albeit rare, Latin word. Consulting a Latin dictionary led McClintock to a passage from De Finibus Bonorum et Malorum (“On the Extremes of Good and Evil”), a first-century B.C. text from the Roman philosopher Cicero.

In particular, the garbled words of lorem ipsum bear an unmistakable resemblance to sections 1.10.32–33 of Cicero''s work, with the most notable passage excerpted below:', 'KULLANIM KOŞULLARI

Until recently, the prevailing view assumed lorem ipsum was born as a nonsense text. “It''s not Latin, though it looks like it, and it actually says nothing,” Before & After magazine answered a curious reader, “Its ‘words’ loosely approximate the frequency with which letters occur in English, which is why at a glance it looks pretty real.”

As Cicero would put it, “Um, not so fast.”

The placeholder text, beginning with the line “Lorem ipsum dolor sit amet, consectetur adipiscing elit”, looks like Latin because in its youth, centuries ago, it was Latin.

Richard McClintock, a Latin scholar from Hampden-Sydney College, is credited with discovering the source behind the ubiquitous filler text. In seeing a sample of lorem ipsum, his interest was piqued by consectetur—a genuine, albeit rare, Latin word. Consulting a Latin dictionary led McClintock to a passage from De Finibus Bonorum et Malorum (“On the Extremes of Good and Evil”), a first-century B.C. text from the Roman philosopher Cicero.

In particular, the garbled words of lorem ipsum bear an unmistakable resemblance to sections 1.10.32–33 of Cicero''s work, with the most notable passage excerpted below:', 'GİZLİLİK SÖZLEŞMESİ

Until recently, the prevailing view assumed lorem ipsum was born as a nonsense text. “It''s not Latin, though it looks like it, and it actually says nothing,” Before & After magazine answered a curious reader, “Its ‘words’ loosely approximate the frequency with which letters occur in English, which is why at a glance it looks pretty real.”

As Cicero would put it, “Um, not so fast.”

The placeholder text, beginning with the line “Lorem ipsum dolor sit amet, consectetur adipiscing elit”, looks like Latin because in its youth, centuries ago, it was Latin.

Richard McClintock, a Latin scholar from Hampden-Sydney College, is credited with discovering the source behind the ubiquitous filler text. In seeing a sample of lorem ipsum, his interest was piqued by consectetur—a genuine, albeit rare, Latin word. Consulting a Latin dictionary led McClintock to a passage from De Finibus Bonorum et Malorum (“On the Extremes of Good and Evil”), a first-century B.C. text from the Roman philosopher Cicero.

In particular, the garbled words of lorem ipsum bear an unmistakable resemblance to sections 1.10.32–33 of Cicero''s work, with the most notable passage excerpted below:', 'UZAK MESAFELİ SATIŞ SÖZLEŞMESİ

Until recently, the prevailing view assumed lorem ipsum was born as a nonsense text. “It''s not Latin, though it looks like it, and it actually says nothing,” Before & After magazine answered a curious reader, “Its ‘words’ loosely approximate the frequency with which letters occur in English, which is why at a glance it looks pretty real.”

As Cicero would put it, “Um, not so fast.”

The placeholder text, beginning with the line “Lorem ipsum dolor sit amet, consectetur adipiscing elit”, looks like Latin because in its youth, centuries ago, it was Latin.

Richard McClintock, a Latin scholar from Hampden-Sydney College, is credited with discovering the source behind the ubiquitous filler text. In seeing a sample of lorem ipsum, his interest was piqued by consectetur—a genuine, albeit rare, Latin word. Consulting a Latin dictionary led McClintock to a passage from De Finibus Bonorum et Malorum (“On the Extremes of Good and Evil”), a first-century B.C. text from the Roman philosopher Cicero.

In particular, the garbled words of lorem ipsum bear an unmistakable resemblance to sections 1.10.32–33 of Cicero''s work, with the most notable passage excerpted below:', 'TESLİMATLAR

Until recently, the prevailing view assumed lorem ipsum was born as a nonsense text. “It''s not Latin, though it looks like it, and it actually says nothing,” Before & After magazine answered a curious reader, “Its ‘words’ loosely approximate the frequency with which letters occur in English, which is why at a glance it looks pretty real.”

As Cicero would put it, “Um, not so fast.”

The placeholder text, beginning with the line “Lorem ipsum dolor sit amet, consectetur adipiscing elit”, looks like Latin because in its youth, centuries ago, it was Latin.

Richard McClintock, a Latin scholar from Hampden-Sydney College, is credited with discovering the source behind the ubiquitous filler text. In seeing a sample of lorem ipsum, his interest was piqued by consectetur—a genuine, albeit rare, Latin word. Consulting a Latin dictionary led McClintock to a passage from De Finibus Bonorum et Malorum (“On the Extremes of Good and Evil”), a first-century B.C. text from the Roman philosopher Cicero.

In particular, the garbled words of lorem ipsum bear an unmistakable resemblance to sections 1.10.32–33 of Cicero''s work, with the most notable passage excerpted below:', 'İPTAL & İADE & DEĞİŞİM

Until recently, the prevailing view assumed lorem ipsum was born as a nonsense text. “It''s not Latin, though it looks like it, and it actually says nothing,” Before & After magazine answered a curious reader, “Its ‘words’ loosely approximate the frequency with which letters occur in English, which is why at a glance it looks pretty real.”

As Cicero would put it, “Um, not so fast.”

The placeholder text, beginning with the line “Lorem ipsum dolor sit amet, consectetur adipiscing elit”, looks like Latin because in its youth, centuries ago, it was Latin.

Richard McClintock, a Latin scholar from Hampden-Sydney College, is credited with discovering the source behind the ubiquitous filler text. In seeing a sample of lorem ipsum, his interest was piqued by consectetur—a genuine, albeit rare, Latin word. Consulting a Latin dictionary led McClintock to a passage from De Finibus Bonorum et Malorum (“On the Extremes of Good and Evil”), a first-century B.C. text from the Roman philosopher Cicero.

In particular, the garbled words of lorem ipsum bear an unmistakable resemblance to sections 1.10.32–33 of Cicero''s work, with the most notable passage excerpted below:');

INSERT INTO public.cargo_company (id, name) VALUES (1, 'Default Kargo');

INSERT INTO public.category (id, slug, name, is_deleted) VALUES (1, 'giyim', 'Giyim', false);
INSERT INTO public.category (id, slug, name, is_deleted) VALUES (2, 'ayakkabi', 'Ayakkabı', false);
INSERT INTO public.category (id, slug, name, is_deleted) VALUES (3, 'aksesuar', 'Aksesuar', false);
INSERT INTO public.category (id, slug, name, is_deleted) VALUES (4, 'ic_giyim', 'İç Giyim', false);
INSERT INTO public.category (id, slug, name, is_deleted) VALUES (5, 'spor', 'Spor', false);
INSERT INTO public.category (id, slug, name, is_deleted) VALUES (6, 'makyaj', 'Makyaj', false);
INSERT INTO public.category (id, slug, name, is_deleted) VALUES (7, 'cilt_bakimi', 'Cilt Bakımı', false);
INSERT INTO public.category (id, slug, name, is_deleted) VALUES (8, 'parfum', 'Parfüm', false);
INSERT INTO public.category (id, slug, name, is_deleted) VALUES (9, 'cep_telefonu', 'Cep Telefonu', false);
INSERT INTO public.category (id, slug, name, is_deleted) VALUES (10, 'bilgisayar', 'Bilgisayar', false);
INSERT INTO public.category (id, slug, name, is_deleted) VALUES (11, 'tablet', 'Tablet', false);
INSERT INTO public.category (id, slug, name, is_deleted) VALUES (12, 'beyaz_esya', 'Beyaz Eşya', false);
INSERT INTO public.category (id, slug, name, is_deleted) VALUES (13, 'diger', 'Diğer', false);

INSERT INTO public.menu (id, name, slug, is_deleted) VALUES (1, 'Kadın', 'kadin', false);
INSERT INTO public.menu (id, name, slug, is_deleted) VALUES (2, 'Erkek', 'erkek', false);
INSERT INTO public.menu (id, name, slug, is_deleted) VALUES (3, 'Çocuk', 'cocuk', false);
INSERT INTO public.menu (id, name, slug, is_deleted) VALUES (4, 'Ev & Yaşam', 'ev_yasam', false);
INSERT INTO public.menu (id, name, slug, is_deleted) VALUES (5, 'Elektronik', 'elektronik', false);

INSERT INTO public.settings (id, is_deleted, name, title, description, keywords, copyright, mail, link, address, phone, footer_text, facebook, instagram, linkedin, twitter, youtube, pinterest) VALUES (1, false, 'symfonyshop', 'Yeni nesil eticaret', 'giyim, elektronik, ev eşyaları, kozmetik binlerce ürün ', 'giyim, elektronik, ev eşyaları, kozmetik', '2020 All Rights Reserved', 'info@symfonyshop.tk', 'symfonyshop.tk', 'İstanbul - Kadıköy', '5999999999', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', NULL, NULL, NULL, NULL, NULL, NULL);

INSERT INTO public.iyzico (id, iyzico_api_key, iyzico_secret_key, iyzico_base_url) VALUES (1, 'api_key', 'secret', 'base_url');

SELECT pg_catalog.setval('public.admin_account_id_seq', 1, true);

SELECT pg_catalog.setval('public.admin_account_profile_id_seq', 1, true);

SELECT pg_catalog.setval('public.admin_permission_id_seq', 57, true);

SELECT pg_catalog.setval('public.admin_profile_id_seq', 1, true);

SELECT pg_catalog.setval('public.admin_profile_permission_id_seq', 57, true);

SELECT pg_catalog.setval('public.category_id_seq', 13, true);

SELECT pg_catalog.setval('public.menu_id_seq', 5, true);

SELECT pg_catalog.setval('public.settings_id_seq', 1, true);

ALTER TABLE ONLY public.address
    ADD CONSTRAINT address_pk PRIMARY KEY (id);

ALTER TABLE ONLY public.admin_account
    ADD CONSTRAINT admin_account_pk PRIMARY KEY (id);

ALTER TABLE ONLY public.admin_account_profile
    ADD CONSTRAINT admin_account_profile_pk PRIMARY KEY (id);

ALTER TABLE ONLY public.admin_permission
    ADD CONSTRAINT admin_permission_pk PRIMARY KEY (id);

ALTER TABLE ONLY public.admin_profile_permission
    ADD CONSTRAINT admin_profile_permission_pk PRIMARY KEY (id);

ALTER TABLE ONLY public.admin_profile
    ADD CONSTRAINT admin_profile_pk PRIMARY KEY (id);

ALTER TABLE ONLY public.bank_accounts
    ADD CONSTRAINT bank_account_pk PRIMARY KEY (id);

ALTER TABLE ONLY public.banner
    ADD CONSTRAINT banner_pk PRIMARY KEY (id);

ALTER TABLE ONLY public.cargo_company
    ADD CONSTRAINT cargo_company_pk PRIMARY KEY (id);

ALTER TABLE ONLY public.cart
    ADD CONSTRAINT cart_pk PRIMARY KEY (id);

ALTER TABLE ONLY public.category_menu
    ADD CONSTRAINT category_menu_pk PRIMARY KEY (id);

ALTER TABLE ONLY public.category
    ADD CONSTRAINT category_pk PRIMARY KEY (id);

ALTER TABLE ONLY public.faq
    ADD CONSTRAINT faq_pk PRIMARY KEY (id);

ALTER TABLE ONLY public.menu
    ADD CONSTRAINT menu_pk PRIMARY KEY (id);

ALTER TABLE ONLY public.order_notice
    ADD CONSTRAINT order_notice_pk PRIMARY KEY (id);

ALTER TABLE ONLY public.product_category
    ADD CONSTRAINT product_category_pk PRIMARY KEY (id);

ALTER TABLE ONLY public.product_comment
    ADD CONSTRAINT product_comment_pk PRIMARY KEY (id);

ALTER TABLE ONLY public.product_photo
    ADD CONSTRAINT product_photo_pk PRIMARY KEY (id);

ALTER TABLE ONLY public.product
    ADD CONSTRAINT product_pk PRIMARY KEY (id);

ALTER TABLE ONLY public.product_variant
    ADD CONSTRAINT product_variant_pk PRIMARY KEY (id);

ALTER TABLE ONLY public.settings
    ADD CONSTRAINT settings_pk PRIMARY KEY (id);

ALTER TABLE ONLY public.user_account_address
    ADD CONSTRAINT user_account_address_pk PRIMARY KEY (id);

ALTER TABLE ONLY public.user_account_favorite
    ADD CONSTRAINT user_account_favorite_pk PRIMARY KEY (id);

ALTER TABLE ONLY public.user_account
    ADD CONSTRAINT user_pk PRIMARY KEY (id);

CREATE UNIQUE INDEX address_id_uindex ON public.address USING btree (id);

CREATE UNIQUE INDEX admin_account_email_uindex ON public.admin_account USING btree (email);

CREATE UNIQUE INDEX admin_account_id_uindex ON public.admin_account USING btree (id);

CREATE UNIQUE INDEX admin_account_mobile_uindex ON public.admin_account USING btree (mobile);

CREATE UNIQUE INDEX admin_account_profile_id_uindex ON public.admin_account_profile USING btree (id);

CREATE UNIQUE INDEX admin_permission_id_uindex ON public.admin_permission USING btree (id);

CREATE UNIQUE INDEX admin_profile_id_uindex ON public.admin_profile USING btree (id);

CREATE UNIQUE INDEX admin_profile_permission_id_uindex ON public.admin_profile_permission USING btree (id);

CREATE UNIQUE INDEX bank_account_id_uindex ON public.bank_accounts USING btree (id);

CREATE UNIQUE INDEX banner_id_uindex ON public.banner USING btree (id);

CREATE UNIQUE INDEX cargo_company_id_uindex ON public.cargo_company USING btree (id);

CREATE UNIQUE INDEX cart_id_uindex ON public.cart USING btree (id);

CREATE UNIQUE INDEX category_id_uindex ON public.category USING btree (id);

CREATE UNIQUE INDEX category_menu_id_uindex ON public.category_menu USING btree (id);

CREATE UNIQUE INDEX faq_id_uindex ON public.faq USING btree (id);

CREATE UNIQUE INDEX menu_id_uindex ON public.menu USING btree (id);

CREATE UNIQUE INDEX order_notice_id_uindex ON public.order_notice USING btree (id);

CREATE UNIQUE INDEX product_category_id_uindex ON public.product_category USING btree (id);

CREATE UNIQUE INDEX product_comment_id_uindex ON public.product_comment USING btree (id);

CREATE UNIQUE INDEX product_id_uindex ON public.product USING btree (id);

CREATE UNIQUE INDEX product_photo_id_uindex ON public.product_photo USING btree (id);

CREATE UNIQUE INDEX product_variant_id_uindex ON public.product_variant USING btree (id);

CREATE UNIQUE INDEX settings_id_uindex ON public.settings USING btree (id);

CREATE UNIQUE INDEX user_account_address_id_uindex ON public.user_account_address USING btree (id);

CREATE UNIQUE INDEX user_account_email_uindex ON public.user_account USING btree (email);

CREATE UNIQUE INDEX user_account_favorite_id_uindex ON public.user_account_favorite USING btree (id);

CREATE UNIQUE INDEX user_id_uindex ON public.user_account USING btree (id);

ALTER TABLE ONLY public.admin_account_profile
    ADD CONSTRAINT admin_account_profile_admin_account_id_fk FOREIGN KEY (admin_account_id) REFERENCES public.admin_account(id);

ALTER TABLE ONLY public.admin_account_profile
    ADD CONSTRAINT admin_account_profile_admin_profile_id_fk FOREIGN KEY (admin_profile_id) REFERENCES public.admin_profile(id);

ALTER TABLE ONLY public.admin_profile_permission
    ADD CONSTRAINT admin_profile_permission_admin_permission_id_fk FOREIGN KEY (admin_permission_id) REFERENCES public.admin_permission(id);

ALTER TABLE ONLY public.admin_profile_permission
    ADD CONSTRAINT admin_profile_permission_admin_profile_id_fk FOREIGN KEY (admin_profile_id) REFERENCES public.admin_profile(id);

ALTER TABLE ONLY public.cart
    ADD CONSTRAINT cart_cargo_company_id_fk FOREIGN KEY (cargo_company_id) REFERENCES public.cargo_company(id);

ALTER TABLE ONLY public.cart
    ADD CONSTRAINT cart_product_id_fk FOREIGN KEY (product_id) REFERENCES public.product(id);

ALTER TABLE ONLY public.cart
    ADD CONSTRAINT cart_product_variant_id_fk FOREIGN KEY (variant_id) REFERENCES public.product_variant(id);

ALTER TABLE ONLY public.cart
    ADD CONSTRAINT cart_user_account_id_fk FOREIGN KEY (user_account_id) REFERENCES public.user_account(id);

ALTER TABLE ONLY public.category_menu
    ADD CONSTRAINT category_menu_category_id_fk FOREIGN KEY (category_id) REFERENCES public.category(id);

ALTER TABLE ONLY public.category_menu
    ADD CONSTRAINT category_menu_menu_id_fk FOREIGN KEY (menu_id) REFERENCES public.menu(id);

ALTER TABLE ONLY public.order_notice
    ADD CONSTRAINT order_notice_bank_accounts_id_fk FOREIGN KEY (bank_id) REFERENCES public.bank_accounts(id);

ALTER TABLE ONLY public.product_comment
    ADD CONSTRAINT product_comment_product_id_fk FOREIGN KEY (product_id) REFERENCES public.product(id);

ALTER TABLE ONLY public.product_comment
    ADD CONSTRAINT product_comment_user_id_fk FOREIGN KEY (user_id) REFERENCES public.user_account(id);

ALTER TABLE ONLY public.product_variant
    ADD CONSTRAINT product_variant_product_id_fk FOREIGN KEY (product_id) REFERENCES public.product(id);

ALTER TABLE ONLY public.user_account_address
    ADD CONSTRAINT user_account_address_address_id_fk FOREIGN KEY (address_id) REFERENCES public.address(id);

ALTER TABLE ONLY public.user_account_address
    ADD CONSTRAINT user_account_address_user_account_id_fk FOREIGN KEY (user_account_id) REFERENCES public.user_account(id);

ALTER TABLE ONLY public.user_account_favorite
    ADD CONSTRAINT user_account_favorite_product_id_fk FOREIGN KEY (product_id) REFERENCES public.product(id);

ALTER TABLE ONLY public.user_account_favorite
    ADD CONSTRAINT user_account_favorite_user_account_id_fk FOREIGN KEY (user_account_id) REFERENCES public.user_account(id);

-- auto-generated definition
CREATE TABLE orders
(
    id SERIAL NOT NULL CONSTRAINT orders_pk PRIMARY KEY,
    user_account_id INTEGER NOT NULL CONSTRAINT orders_user_account_id_fk REFERENCES user_account,
    order_id INTEGER NOT NULL,
    product_id INTEGER NOT NULL CONSTRAINT orders_product_id_fk REFERENCES product,
    product_name VARCHAR NOT NULL,
    product_price REAL NOT NULL,
    product_quantity INTEGER NOT NULL,
    order_total_amount REAL NOT NULL,
    cargo_company VARCHAR NOT NULL,
    cargo_price REAL NOT NULL,
    product_pic VARCHAR NOT NULL,
    variant_title VARCHAR,
    variant_selection VARCHAR NOT NULL,
    shipping_address_detail JSON NOT NULL,
    billing_address_detail JSON NOT NULL,
    payment_selection VARCHAR NOT NULL,
    created_at TIMESTAMP DEFAULT NOW() NOT NULL,
    order_ip VARCHAR NOT NULL,
    is_approved BOOLEAN DEFAULT FALSE NOT NULL,
    is_shipped BOOLEAN DEFAULT FALSE NOT NULL,
    cargo_send_code VARCHAR,
    raw_result TEXT
);

create unique index orders_id_uindex
    on orders (id);

create index orders_is_approved_index
    on orders (is_approved);

create index orders_is_shipped_index
    on orders (is_shipped);
