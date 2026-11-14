<?php /** ปฏิทินการปฏิบัติงาน (Admin Schedule Calendar) */ ?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
  <div>
    <h5 class="mb-0 fw-bold"><i class="fa-solid fa-calendar-days text-gold me-2"></i>ปฏิทินงานและการปฏิบัติงาน</h5>
    <span class="text-muted small">ตารางลงงานและภารกิจประจำวัน เลื่อนดูแต่ละเดือนได้สะดวกรวดเร็ว</span>
  </div>
  <div class="d-flex gap-2">
    <a href="<?= url('admin/schedules/create') ?>" class="btn btn-gold btn-sm">
      <i class="fa-solid fa-plus me-1"></i> ลงงานใหม่
    </a>
  </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
  <div class="card-body p-3 p-md-4">
    <!-- Quick Action Tip -->
    <div class="d-flex flex-wrap align-items-center gap-3 mb-3 pb-3 border-bottom small text-muted">
      <span><i class="fa-solid fa-circle-info text-primary me-1"></i> คลิกวันที่บนปฏิทินเพื่อเพิ่มงาน</span>
    </div>

    <!-- Calendar Mount Point -->
    <div id="adminScheduleCalendar" style="min-height: 700px;"></div>
  </div>
</div>

<!-- Modal แสดงรายละเอียดงานเมื่อคลิกบนปฏิทิน -->
<div class="modal fade" id="scheduleDetailModal" tabindex="-1" aria-labelledby="scheduleDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold text-dark" id="schedTitle">ชื่องาน</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-3">
        <div id="schedDescBox" class="text-secondary small mb-3" style="white-space: pre-line; line-height: 1.6;"></div>
        <div id="schedCoverContainer" class="text-center" style="display:none;">
          <img id="schedCover" src="" class="img-fluid rounded border" style="max-height: 380px; width: 100%; object-fit: contain;">
        </div>
      </div>
      <div class="modal-footer border-0 pt-0 d-flex justify-content-between">
        <a id="schedDeleteBtn" href="#" class="btn btn-sm btn-outline-danger" data-confirm="ต้องการลบงานนี้ออกจากปฏิทินหรือไม่?">
          <i class="fa-solid fa-trash me-1"></i> ลบงานนี้
        </a>
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">ปิด</button>
          <a id="schedEditBtn" href="#" class="btn btn-sm btn-primary">
            <i class="fa-solid fa-pen me-1"></i> แก้ไขงานนี้
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- FullCalendar Library -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

<style>
/* Modern Minimalist FullCalendar Theme */
.fc {
  font-family: 'Sarabun', -apple-system, BlinkMacSystemFont, sans-serif;
  --fc-border-color: #f1f3f5;
  --fc-today-bg-color: rgba(212, 160, 23, 0.08);
  --fc-page-bg-color: transparent;
}
.fc .fc-toolbar.fc-header-toolbar {
  margin-bottom: 1.5rem;
  flex-wrap: wrap;
  gap: 0.75rem;
}
.fc .fc-toolbar-title {
  font-size: 1.35rem;
  font-weight: 700;
  color: #1a2233;
}
.fc .fc-button {
  background-color: #fff;
  border-color: #dee2e6;
  color: #495057;
  font-size: 0.875rem;
  font-weight: 500;
  padding: 0.4rem 0.85rem;
  border-radius: 8px;
  box-shadow: 0 1px 2px rgba(0,0,0,0.04);
  transition: all 0.15s ease-in-out;
}
.fc .fc-button:hover {
  background-color: #f8f9fa;
  border-color: #ced4da;
  color: #212529;
}
.fc .fc-button-primary:not(:disabled).fc-button-active,
.fc .fc-button-primary:not(:disabled):active {
  background-color: #0b1a30;
  border-color: #0b1a30;
  color: #fff;
  box-shadow: none;
}
.fc .fc-button:focus {
  box-shadow: 0 0 0 0.2rem rgba(11, 26, 48, 0.15);
}
.fc-theme-standard th {
  padding: 0.75rem 0.25rem;
  background-color: #f8f9fa;
  font-weight: 600;
  color: #6c757d;
  font-size: 0.85rem;
  border: 1px solid #f1f3f5;
}
.fc-theme-standard td {
  border: 1px solid #f1f3f5;
  transition: background-color 0.15s;
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
  cursor: pointer;
}
.fc .fc-daygrid-day-frame:hover {
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
.fc .fc-day-today {
  background-color: transparent !important;
}

/* การ์ดงานแบบมีรูปภาพในหน้าแอดมิน */
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
.custom-cal-card.no-image {
  border-left: 3px solid #0d6efd;
}
.custom-cal-card.no-image .custom-cal-body {
  padding: 6px 8px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('adminScheduleCalendar');
  const modalEl = document.getElementById('scheduleDetailModal');
  const detailModal = new bootstrap.Modal(modalEl);

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    height: 'auto',
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
    dayMaxEvents: false, // แสดงทุกงานครบถ้วนในช่องทันที ไม่ซ่อนเป็นปุ่ม +ดูเพิ่ม ให้เห็นชัดเจนเลย
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
    selectable: true,
    // เมื่อคลิกวันที่ว่างๆ ให้เปิดหน้าสร้างงานโดยมีวันที่นั้นติดไปด้วย
    dateClick: function(info) {
      window.location.href = '<?= url('admin/schedules/create') ?>?date=' + encodeURIComponent(info.dateStr);
    },
    events: function(fetchInfo, successCallback, failureCallback) {
      const url = '<?= url('admin/schedules/feed') ?>?start=' + encodeURIComponent(fetchInfo.startStr) + '&end=' + encodeURIComponent(fetchInfo.endStr);
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
          console.error('Error fetching schedules:', err);
          failureCallback(err);
        });
    },
    eventClick: function(info) {
      info.jsEvent.preventDefault();
      const p = info.event.extendedProps;

      const titleEl = document.getElementById('schedTitle');
      if (titleEl) {
        titleEl.textContent = p.title || '';
      }

      // Image Cover
      const coverBox = document.getElementById('schedCoverContainer');
      const coverImg = document.getElementById('schedCover');
      if (coverBox && coverImg) {
        if (p.image) {
          coverImg.src = p.image;
          coverBox.style.display = 'block';
        } else {
          coverBox.style.display = 'none';
        }
      }

      // Description
      const descBox = document.getElementById('schedDescBox');
      if (descBox) {
        if (p.description && p.description.trim() !== '') {
          descBox.textContent = p.description;
          descBox.style.display = 'block';
        } else {
          descBox.style.display = 'none';
        }
      }

      // Action links
      const editBtn = document.getElementById('schedEditBtn');
      const delBtn = document.getElementById('schedDeleteBtn');
      if (editBtn) editBtn.href = p.editUrl;
      if (delBtn) delBtn.href = p.deleteUrl;

      detailModal.show();
    }
  });

  calendar.render();
});
</script>
