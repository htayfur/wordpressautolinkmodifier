# Auto External Link Modifier

WordPress yazı ve sayfa içeriklerindeki dış bağlantılara özel `rel` ve `target="_blank"` ekleyen bir eklenti. İç bağlantılar ve resmi kurum web siteleri hariç tüm linkleri değiştirir.

## ✅ Tamamlanan Özellikler

1. Bağlantı İşleme:
   - ✅ WordPress yazı ve sayfa içeriklerini kontrol etme
   - ✅ Widget ve blok içeriklerini kontrol etme
   - ✅ Dış bağlantılara target="_blank" ekleme
   - ✅ İç bağlantıları otomatik hariç tutma
   - ✅ Resmi kurumları hariç tutma

2. REL Özellikleri Yönetimi:
   - ✅ WordPress admin panelinden kolay yönetim
   - ✅ Özelleştirilebilir REL özellikleri:
     - `noopener` - Yeni sayfanın window.opener'a erişimini engeller
     - `noreferrer` - Referans bilgisinin iletilmesini engeller
     - `nofollow` - Arama motorlarına bağlantıyı takip etmemesini söyler
     - `sponsored` - Sponsorlu/reklamlı içerik bağlantılarını işaretler
     - `ugc` - Kullanıcı tarafından oluşturulan içerik bağlantılarını işaretler

3. Özel Domain Yönetimi:
   - ✅ Özel domain listesi ekleme
   - ✅ Domain formatı otomatik kontrolü
   - ✅ Her satıra bir domain girişi
   - ✅ Kolay domain yönetimi

4. Resmi Domain Kontrolü:
   - ✅ Global devlet domainleri (.gov, .gov.uk, .gov.au)
   - ✅ Global eğitim domainleri (.edu, .ac.uk, .edu.au)
   - ✅ Askeri domainler (.mil)
   - ✅ Ülke spesifik domainler (gov.tr, edu.tr, gov.de, edu.fr)

5. Performans İyileştirmeleri:
   - ✅ DOM işleme optimizasyonu
   - ✅ Hata yakalama ve loglama
   - ✅ Gereksiz işlemleri atlama
   - ✅ Minify edilmiş CSS/JS

6. Gelişmiş Ayarlar:
   - ✅ Modern ve kullanıcı dostu arayüz
   - ✅ Gerçek zamanlı domain validasyonu
   - ✅ Görsel geri bildirimler
   - ✅ Yardımcı açıklamalar

7. Çoklu Dil Desteği:
   - ✅ Türkçe dil dosyaları
   - ✅ Özellik açıklamaları çevirileri
   - ✅ Admin panel çevirileri

## Gereksinimler

- WordPress 5.0+
- PHP 7.4+

## Kurulum

1. Eklenti zip dosyasını indirin
2. WordPress admin paneli > Eklentiler > Yeni Ekle
3. "Eklenti Yükle" butonuna tıklayın ve zip dosyasını seçin
4. "Şimdi Yükle" ve ardından "Etkinleştir"
5. Ayarlar > Dış Bağlantılar menüsünden yapılandırın

## Kullanım

1. Ayarlar > Dış Bağlantılar menüsüne gidin
2. REL özelliklerini seçin
3. İsteğe bağlı olarak özel domain listesi ekleyin
4. Değişiklikleri kaydedin
5. Eklenti otomatik olarak içeriklerdeki bağlantıları düzenleyecektir

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

### 1.1.0
- Widget içerik desteği eklendi
- Özel domain listesi özelliği eklendi
- Gelişmiş ayar sayfası eklendi
- Admin panel CSS/JS eklendi
- Performans iyileştirmeleri yapıldı
- Hata yakalama geliştirildi
