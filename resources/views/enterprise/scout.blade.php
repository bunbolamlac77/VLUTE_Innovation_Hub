@extends('layouts.main')

@section('title', 'Thợ săn Giải pháp - VLUTE Innovation Hub')

@section('content')
    {{-- Breadcrumb --}}
    <section class="container" style="padding: 24px 0 16px;">
        <nav style="display: flex; align-items: center; gap: 8px; color: var(--muted); font-size: 14px;">
            <a href="/" style="color: var(--brand-navy);">Trang chủ</a>
            <span>/</span>
            <span>Thợ săn Giải pháp</span>
        </nav>
    </section>

    {{-- Main Content --}}
    <section class="container" style="padding: 32px 0 64px;">
        <div style="max-width: 1000px; margin: 0 auto;">
            <div class="card" style="box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); background: linear-gradient(135deg, #0a0f5a 0%, #1a1f7a 100%); color: white; border-radius: 16px; padding: 48px;">
                <div style="text-align: center; margin-bottom: 40px;">
                    <div style="font-size: 48px; margin-bottom: 16px;">🎯</div>
                    <h1 style="margin: 0 0 16px; font-size: 36px; font-weight: 800; letter-spacing: -0.01em;">Thợ săn Giải pháp</h1>
                    <p style="margin: 0; font-size: 16px; color: rgba(255, 255, 255, 0.8); line-height: 1.6;">
                        Doanh nghiệp của bạn đang gặp vấn đề gì? Hãy mô tả nó bằng ngôn ngữ tự nhiên.<br>
                        AI sẽ tìm kiếm các giải pháp tương đồng từ kho dữ liệu ý tưởng của sinh viên.
                    </p>
                </div>

                {{-- Search Input --}}
                <div style="display: flex; gap: 12px; margin-bottom: 24px;">
                    <input type="text" id="problem-input" 
                        placeholder="Ví dụ: Cần hệ thống điểm danh tự động bằng khuôn mặt chi phí thấp..."
                        style="flex: 1; padding: 16px 20px; border: 2px solid rgba(255, 255, 255, 0.3); border-radius: 12px; font-size: 16px; background: rgba(255, 255, 255, 0.1); color: white; transition: all 0.3s ease;"
                        onfocus="this.style.borderColor='rgba(255, 255, 255, 0.8)'; this.style.background='rgba(255, 255, 255, 0.15)'; this.style.boxShadow='0 0 0 4px rgba(255, 255, 255, 0.1)';"
                        onblur="this.style.borderColor='rgba(255, 255, 255, 0.3)'; this.style.background='rgba(255, 255, 255, 0.1)'; this.style.boxShadow='none';">
                    <button onclick="scoutSolutions()" 
                        style="padding: 16px 32px; background: #4f46e5; color: white; border: none; border-radius: 12px; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);"
                        onmouseover="this.style.background='#4338ca'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(79, 70, 229, 0.4)';"
                        onmouseout="this.style.background='#4f46e5'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(79, 70, 229, 0.3)';">
                        🔍 Tìm kiếm
                    </button>
                </div>

                {{-- Loading State --}}
                <div id="scout-loading" class="hidden" style="text-align: center; padding: 40px 20px;">
                    <div style="display: inline-block; width: 48px; height: 48px; border: 4px solid rgba(255, 255, 255, 0.3); border-top-color: white; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                    <p style="margin-top: 16px; font-size: 16px; color: rgba(255, 255, 255, 0.8);">⏳ Đang quét kho dữ liệu vector...</p>
                </div>

                {{-- Success Notification --}}
                <div id="found-notification" class="hidden" style="margin-bottom: 24px; padding: 20px 24px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 12px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); animation: slideDown 0.4s ease-out;">
                    <div style="display: flex; align-items: center; gap: 16px; color: white;">
                        <div style="font-size: 32px;">✅</div>
                        <div style="flex: 1;">
                            <p style="margin: 0; font-size: 18px; font-weight: 700;">Đã tìm thấy giải pháp!</p>
                            <p style="margin: 4px 0 0; font-size: 14px; opacity: 0.9;">Hệ thống đã tìm thấy các ý tưởng phù hợp với vấn đề của bạn.</p>
                        </div>
                    </div>
                </div>

                {{-- No Results State --}}
                <div id="no-results" class="hidden" style="text-align: center; padding: 40px 20px; background: rgba(255, 255, 255, 0.1); border-radius: 12px; border: 2px dashed rgba(255, 255, 255, 0.3);">
                    <div style="font-size: 40px; margin-bottom: 12px;">🔍</div>
                    <p style="margin: 0; font-size: 16px; color: rgba(255, 255, 255, 0.8);">Không tìm thấy giải pháp nào phù hợp trên 65%.</p>
                    <p style="margin: 8px 0 0; font-size: 14px; color: rgba(255, 255, 255, 0.6);">Hãy thử mô tả vấn đề chi tiết hơn hoặc sử dụng từ khóa khác.</p>
                </div>
            </div>

            {{-- Results Container --}}
            <div id="scout-results" class="hidden" style="margin-top: 32px;">
                {{-- Results will be dynamically inserted here by JavaScript --}}
            </div>
        </div>
    </section>

    {{-- Spin Animation --}}
    <style>
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .hidden {
            display: none !important;
        }
    </style>

    @push('scripts')
    <script>
        function scoutSolutions() {
            const problem = document.getElementById('problem-input').value.trim();
            
            if (!problem) {
                alert('Vui lòng nhập mô tả vấn đề!');
                return;
            }

            if (problem.length < 10) {
                alert('Vui lòng mô tả vấn đề chi tiết hơn (ít nhất 10 ký tự).');
                return;
            }

            // Show loading, hide results
            document.getElementById('scout-loading').classList.remove('hidden');
            document.getElementById('scout-results').classList.add('hidden');
            document.getElementById('no-results').classList.add('hidden');
            document.getElementById('found-notification').classList.add('hidden');

            fetch('{{ route("ai.scout") }}', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                },
                body: JSON.stringify({ problem: problem })
            })
            .then(async res => {
                const data = await res.json();
                if (!res.ok) {
                    // Xử lý lỗi từ server (400, 500, etc.)
                    const errorMsg = data.message || data.error || 'Có lỗi xảy ra khi tìm kiếm giải pháp.';
                    throw new Error(errorMsg);
                }
                return data;
            })
            .then(data => {
                const container = document.getElementById('scout-results');
                const resultCount = document.getElementById('result-count');
                container.innerHTML = '';

                // Kiểm tra nếu không có kết quả hoặc found = 0
                if (!data.found || data.found === 0) {
                    document.getElementById('no-results').classList.remove('hidden');
                    document.getElementById('scout-results').classList.add('hidden');
                    document.getElementById('found-notification').classList.add('hidden');
                } else {
                    // Hiển thị thông báo tìm thấy giải pháp
                    document.getElementById('found-notification').classList.remove('hidden');
                    document.getElementById('no-results').classList.add('hidden');
                    
                    // Add header with count
                    const header = document.createElement('div');
                    header.style.cssText = 'margin-bottom: 24px;';
                    header.innerHTML = `
                        <h2 style="margin: 0 0 8px; font-size: 24px; font-weight: 700; color: #0f172a;">
                            Kết quả tìm kiếm
                        </h2>
                        <p style="margin: 0; font-size: 14px; color: var(--muted);">Tìm thấy <strong>${data.found}</strong> ý tưởng phù hợp (hiển thị top 5)</p>
                    `;
                    container.appendChild(header);

                    // Add result items
                    const resultsList = document.createElement('div');
                    resultsList.style.cssText = 'display: grid; grid-template-columns: 1fr; gap: 16px;';

                    data.results.forEach((item, index) => {
                        // Color based on score
                        let scoreColor = '#10b981'; // green
                        let scoreBgColor = '#ecfdf5';
                        if (item.score < 75) {
                            scoreColor = '#3b82f6'; // blue
                            scoreBgColor = '#eff6ff';
                        }
                        if (item.score < 70) {
                            scoreColor = '#f59e0b'; // amber
                            scoreBgColor = '#fffbeb';
                        }

                        const resultItem = document.createElement('div');
                        resultItem.style.cssText = `
                            display: flex;
                            align-items: flex-start;
                            gap: 20px;
                            padding: 24px;
                            background: white;
                            border: 2px solid #e5e7eb;
                            border-radius: 12px;
                            transition: all 0.3s ease;
                            cursor: pointer;
                            animation: slideIn 0.4s ease-out ${index * 0.1}s both;
                        `;

                        resultItem.onmouseover = function() {
                            this.style.borderColor = '#0a0f5a';
                            this.style.boxShadow = '0 8px 24px rgba(10, 15, 90, 0.12)';
                            this.style.transform = 'translateY(-4px)';
                        };

                        resultItem.onmouseout = function() {
                            this.style.borderColor = '#e5e7eb';
                            this.style.boxShadow = 'none';
                            this.style.transform = 'translateY(0)';
                        };

                        resultItem.innerHTML = `
                            <div style="flex: 1;">
                                <h3 style="margin: 0 0 8px; font-size: 18px; font-weight: 700; color: #0f172a;">
                                    <a href="/ideas/${item.slug}" target="_blank" style="color: #0a0f5a; text-decoration: none; transition: color 0.2s ease;"
                                        onmouseover="this.style.color='#4f46e5';"
                                        onmouseout="this.style.color='#0a0f5a';">
                                        ${item.title}
                                    </a>
                                </h3>
                                <p style="margin: 0 0 12px; font-size: 14px; color: #6b7280; line-height: 1.6;">
                                    ${item.abstract}...
                                </p>
                                <p style="margin: 0; font-size: 13px; color: #9ca3af;">
                                    👤 Tác giả: <strong>${item.author}</strong>
                                </p>
                            </div>
                            <div style="
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                justify-content: center;
                                padding: 16px 24px;
                                background: ${scoreBgColor};
                                border-radius: 12px;
                                min-width: 100px;
                                text-align: center;
                                flex-shrink: 0;
                            ">
                                <div style="font-size: 32px; font-weight: 800; color: ${scoreColor};">
                                    ${item.score}%
                                </div>
                                <div style="font-size: 12px; color: ${scoreColor}; font-weight: 600; margin-top: 4px;">
                                    Phù hợp
                                </div>
                            </div>
                        `;

                        resultsList.appendChild(resultItem);
                    });

                    container.appendChild(resultsList);
                    container.classList.remove('hidden');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                // Hiển thị lỗi chi tiết hơn
                const errorMsg = err.message || 'Có lỗi xảy ra khi kết nối với hệ thống AI.';
                alert('❌ ' + errorMsg + '\n\nVui lòng kiểm tra:\n- API key đã được cấu hình chưa (GEMINI_API_KEY hoặc OPENAI_API_KEY)\n- Kết nối mạng có ổn định không');
                document.getElementById('scout-loading').classList.add('hidden');
                document.getElementById('scout-results').classList.add('hidden');
                document.getElementById('no-results').classList.add('hidden');
                document.getElementById('found-notification').classList.add('hidden');
            })
            .finally(() => {
                document.getElementById('scout-loading').classList.add('hidden');
            });
        }

        // Allow Enter key to search
        document.getElementById('problem-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                scoutSolutions();
            }
        });

        // Add slide-in animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
    @endpush
@endsection

