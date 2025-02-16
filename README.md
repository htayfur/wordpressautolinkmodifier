# Auto External Link Modifier

WordPress yazı ve sayfa içeriklerindeki dış bağlantılara özel `rel` ve `target="_blank"` ekleyen bir eklenti. İç bağlantılar ve resmi kurum web siteleri hariç tüm linkleri değiştirir.

## Özellikler

- Dış bağlantılara otomatik olarak `target="_blank"` ekler
- WordPress admin panelinden `rel` özelliği yönetimi:
  - `noopener` - Yeni sayfanın window.opener'a erişimini engeller
  - `noreferrer` - Referans bilgisinin iletilmesini engeller
  - `nofollow` - Arama motorlarına bağlantıyı takip etmemesini söyler
  - `sponsored` - Sponsorlu/reklamlı içerik bağlantılarını işaretler
  - `ugc` - Kullanıcı tarafından oluşturulan içerik bağlantılarını işaretler

## Hariç Tutulan Bağlantılar

- İç bağlantılar (aynı domain)
- Resmi kurumlar:
  - Global devlet domainleri (.gov, .gov.uk, .gov.au)
  - Global eğitim domainleri (.edu, .ac.uk, .edu.au)
  - Askeri domainler (.mil)
  - Ülke spesifik domainler (gov.tr, edu.tr, gov.de, edu.fr)

## Gereksinimler

- WordPress 5.0+
- PHP 7.4+

## Kurulum

1. Eklenti zip dosyasını indirin
2. WordPress admin paneli > Eklentiler > Yeni Ekle
3. "Eklenti Yükle" butonuna tıklayın ve zip dosyasını seçin
4. "Şimdi Yükle" ve ardından "Etkinleştir"
5. Ayarlar > Dış Bağlantılar menüsünden REL özelliklerini yapılandırın

## Kullanım

1. Ayarlar > Dış Bağlantılar menüsünden REL özelliklerini seçin
2. Değişiklikleri kaydedin
3. Eklenti otomatik olarak yazı ve sayfalardaki dış bağlantıları işleyecektir

## Lisans

GPL v2 veya üzeri

## Geliştirici

[Hakan Tayfur](https://htayfur.com)

## Sürüm Geçmişi

### 1.0.0 
- İlk sürüm
- WordPress yazı ve sayfalarda dış bağlantı düzenleme
- Özelleştirilebilir REL özellikleri
- Global ve yerel resmi domain desteği
- Türkçe dil desteği
