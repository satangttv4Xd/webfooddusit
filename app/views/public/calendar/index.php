<?php
/**
 * ปฏิทินงานและการปฏิบัติงาน (หน้าสาธารณะ / บุคลากร)
 */
?>
<?= page_header('ปฏิทินการปฏิบัติงาน', ['ปฏิทินงาน' => '']) ?>

<section class="section py-5">
  <div class="container">
    <!-- Calendar Card Container -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
      <div class="card-body p-3 p-md-4">
        <!-- FullCalendar Mount Point -->
        <div id="publicScheduleCalendar"></div>
      </div>
    </div>
  </div>
</section>

<!-- Modal รายละเอียดงาน -->
<div class="modal fade" id="publicScheduleModal" tabindex="-1" aria-labelledby="publicScheduleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold text-dark" id="pubSchedTitle">ชื่องาน</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-3">
        <div id="pubSchedDescBox" class="text-secondary small mb-3" style="white-space: pre-line; line-height: 1.6;"></div>
        <div id="pubSchedCoverContainer" class="text-center" style="display:none;">
          <img id="pubSchedCover" src="" class="img-fluid rounded-3 border" style="max-height: 380px; width: 100%; object-fit: contain;">
        </div>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-light btn-sm rounded-pill px-4" data-bs-dismiss="modal">ปิด</button>
      </div>
    </div>
  </div>
</div>

<!-- FullCalendar Library & Minimalist Styling -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

<style>
/* Modern Minimalist Calendar Theme */
.fc {
  font-family: 'Sarabun', -apple-system, BlinkMacSystemFont, sans-serif;
  --fc-border-color: #f1f3f5;
  --fc-today-bg-color: rgba(212, 160, 23, 0.08);
  --fc-page-bg-color: #ffffff;
}
.fc .fc-toolbar.fc-header-toolbar {
  margin-bottom: 1.5rem;
  flex-wrap: wrap;
  gap: 0.75rem;
}
.fc .fc-toolbar-title {
  font-size: 1.35rem;
  font-weight: 700;
  color: var(--navy, #0b1a30);
}
.fc .fc-button {
  background-color: #fff;
  border: 1px solid #dee2e6;
  color: #495057;
  font-size: 0.875rem;
  font-weight: 500;
  padding: 0.4rem 0.85rem;
  border-radius: 8px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.04);
  transition: all 0.15s ease-in-out;
}
.fc .fc-button:hover {
  background-color: #f8f9fa;
  border-color: #ced4da;
  color: #0b1a30;
}
.fc .fc-button-primary:not(:disabled).fc-button-active,
.fc .fc-button-primary:not(:disabled):active {
  background-color: var(--navy, #0b1a30);
  border-color: var(--navy, #0b1a30);
  color: #fff;
  box-shadow: none;
}
/* Table Grid Uniformity & Professional Proportions */
.fc .fc-scrollgrid {
  border: 1px solid #edf0f2 !important;
  border-radius: 12px;
  overflow: hidden;
}
.fc-theme-standard th {
  padding: 0.9rem 0.5rem;
  background-color: #f8fafc;
  font-weight: 600;
  color: var(--navy, #0b1a30);
  font-size: 0.88rem;
  border: 1px solid #edf0f2;
  text-align: center;
}
/* ปิด Scrollbar ภายในตารางและช่องวันทั้งหมด */
.fc .fc-scroller {
  overflow: visible !important;
  height: auto !important;
}
.fc-daygrid-day-events {
  overflow: visible !important;
}
.fc-daygrid-day-bottom {
  display: none !important;
}

/* ช่องวันยืดหยุ่นตามเนื้อหาภาพจริง */
.fc .fc-daygrid-day-frame {
  min-height: 110px !important;
  height: auto !important;
  display: flex;
  flex-direction: column;
  padding: 6px;
  background-color: #ffffff;
  transition: background-color 0.15s ease;
  overflow: visible !important;
}
.fc .fc-daygrid-day:hover .fc-daygrid-day-frame {
  background-color: #fafbfc;
}
.fc .fc-daygrid-day-top {
  flex-direction: row;
  justify-content: flex-end;
  margin-bottom: 4px;
}
.fc .fc-daygrid-day-number {
  font-size: 0.85rem;
  font-weight: 600;
  color: #64748b;
  padding: 2px 6px;
  line-height: 1;
}
/* วันต่างเดือนให้สีจางลง */
.fc .fc-day-other .fc-daygrid-day-number {
  color: #cbd5e1;
}
.fc .fc-day-other .fc-daygrid-day-frame {
  background-color: #fcfdfe;
}
.fc .fc-day-today {
  background-color: transparent !important;
}

/* Event Container & Minimal Card */
.fc .fc-daygrid-day-events {
  flex-grow: 1;
  margin-top: 0 !important;
  display: flex;
  flex-direction: column;
  gap: 4px;
}
.fc-daygrid-event-harness {
  margin: 0 !important;
  width: 100%;
}
.fc-event {
  border: none !important;
  background: transparent !important;
  padding: 0 !important;
  border-radius: 8px !important;
  cursor: pointer;
  overflow: hidden;
  box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.fc-event:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 10px rgba(0,0,0,0.12);
}
/* ตกแต่งปุ่ม + ดูอีก X งาน ให้สวยงามสไตล์ Minimal Pill */
.fc .fc-daygrid-more-link {
  font-size: 0.75rem;
  font-weight: 600;
  color: #0b1a30 !important;
  background-color: #f1f5f9;
  border: 1px solid #e2e8f0;
  border-radius: 20px;
  padding: 2px 8px;
  margin: 3px 0 1px 0;
  text-align: center;
  display: block;
  text-decoration: none !important;
  transition: all 0.2s ease;
}
.fc .fc-daygrid-more-link:hover {
  background-color: #e2e8f0;
  color: #000 !important;
  transform: translateY(-1px);
}
.fc-more-popover {
  border-radius: 12px !important;
  border: 1px solid #e2e8f0 !important;
  box-shadow: 0 10px 25px rgba(0,0,0,0.12) !important;
  overflow: hidden;
}
.fc-more-popover .fc-popover-header {
  background-color: #f8fafc !important;
  font-weight: 700;
  font-size: 0.88rem;
  padding: 8px 12px !important;
  color: #0b1a30;
}
.fc-more-popover .fc-popover-body {
  padding: 10px !important;
  max-height: 380px;
  overflow-y: auto;
}

/* การ์ดงานแบบมีรูปภาพ (Clean & Minimal Media Card) */
.custom-cal-card {
  display: flex;
  flex-direction: column;
  width: 100%;
  border-radius: 8px;
  overflow: hidden;
  background-color: #ffffff;
  border: 1px solid #e2e8f0;
}
.custom-cal-img-wrap {
  width: 100%;
  height: 85px;
  overflow: hidden;
  background-color: #f1f5f9;
  position: relative;
}
.custom-cal-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  transition: transform 0.3s ease;
}
.fc-event:hover .custom-cal-img {
  transform: scale(1.04);
}
.custom-cal-body {
  padding: 5px 8px 6px 8px;
  background-color: #ffffff;
}
.custom-cal-title {
  font-size: 0.78rem;
  font-weight: 600;
  color: #1e293b;
  line-height: 1.3;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  word-break: break-word;
}

/* การ์ดงานแบบไม่มีรูปภาพ */
.custom-cal-card.no-image {
  border-left: 3px solid #0d6efd;
}
.custom-cal-card.no-image .custom-cal-body {
  padding: 6px 8px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('publicScheduleCalendar');
  const modalEl = document.getElementById('publicScheduleModal');
  const pubModal = new bootstrap.Modal(modalEl);

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    height: 'auto', // คำนวณความสูงตามเนื้อหาจริงอัตโนมัติ หมดปัญหามี Scrollbar ด้านใน
    contentHeight: 'auto',
    locale: 'th',
    headerToolbar: {
      left: 'prev,next',
      center: 'title',
      right: 'today'
    },
    buttonText: {
      today: 'วันนี้'
    },
    dayMaxEvents: false, // แสดงทุกงานครบถ้วนทันที ไม่ซ่อนเป็นปุ่ม +ดูเพิ่ม ให้เห็นภาพชัดเจนเลย
    eventContent: function(arg) {
      const p = arg.event.extendedProps;
      const color = p.color || arg.event.backgroundColor || '#0d6efd';

      if (p.image) {
        return {
          html: `
            <div class="custom-cal-card" style="border-top: 3px solid ${color};">
              <div class="custom-cal-img-wrap">
                <img src="${p.image}" class="custom-cal-img" alt="${arg.event.title}" loading="lazy" decoding="async" onerror="this.closest('.custom-cal-img-wrap').style.display='none'">
              </div>
              <div class="custom-cal-body">
                <div class="custom-cal-title">${arg.event.title}</div>
              </div>
            </div>
          `
        };
      } else {
        return {
          html: `
            <div class="custom-cal-card no-image" style="border-left-color: ${color};">
              <div class="custom-cal-body">
                <div class="custom-cal-title" style="color: ${color};">${arg.event.title}</div>
              </div>
            </div>
          `
        };
      }
    },
    events: function(fetchInfo, successCallback, failureCallback) {
      const url = '<?= url('calendar/feed') ?>?start=' + encodeURIComponent(fetchInfo.startStr) + '&end=' + encodeURIComponent(fetchInfo.endStr);
      fetch(url)
        .then(res => res.json())
        .then(data => {
          const events = data.map(item => ({
            id: item.id,
            title: item.title,
            start: item.start,
            end: item.end,
            backgroundColor: item.backgroundColor,
            borderColor: item.borderColor,
            textColor: item.textColor,
            extendedProps: item
          }));
          successCallback(events);
        })
        .catch(err => {
          console.error('Error fetching calendar feed:', err);
          failureCallback(err);
        });
    },
    eventClick: function(info) {
      info.jsEvent.preventDefault();
      const p = info.event.extendedProps;

      // Title
      const titleEl = document.getElementById('pubSchedTitle');
      if (titleEl) {
        titleEl.textContent = p.title || '';
      }

      // Cover Image
      const coverBox = document.getElementById('pubSchedCoverContainer');
      const coverImg = document.getElementById('pubSchedCover');
      if (coverBox && coverImg) {
        if (p.image) {
          coverImg.src = p.image;
          coverBox.style.display = 'block';
        } else {
          coverBox.style.display = 'none';
        }
      }

      // Description
      const descBox = document.getElementById('pubSchedDescBox');
      if (descBox) {
        if (p.description && p.description.trim() !== '') {
          descBox.textContent = p.description;
          descBox.style.display = 'block';
        } else {
          descBox.style.display = 'none';
        }
      }

      pubModal.show();
    }
  });

  calendar.render();
});
</script>
